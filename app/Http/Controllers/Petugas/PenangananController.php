<?php
/**
 * PBI-07 — Update Status Penanganan + Upload Foto Dokumentasi
 * TANGGUNG JAWAB: Falah Adhi Chandra
 */
namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Services\{AssignmentService, SlaService, NotifikasiService};
use Illuminate\Http\Request;

class PenangananController extends Controller
{
    public function __construct(
        private AssignmentService  $assignmentService,
        private SlaService         $slaService,
        private NotifikasiService  $notifikasiService
    ) {}

    public function index()
    {
        $petugasId = auth()->user()->petugas?->id;

        abort_if(!$petugasId, 403, 'Akun Anda belum terdaftar sebagai petugas.');

        $tugasAktif = Assignment::with(['pengaduan.kategori', 'pengaduan.zona', 'pengaduan.sla'])
            ->where('petugas_id', $petugasId)
            ->whereIn('status_assignment', ['ditugaskan', 'diproses'])
            ->latest()
            ->get();

        $tugasSelesai = Assignment::with(['pengaduan.kategori', 'pengaduan.zona'])
            ->where('petugas_id', $petugasId)
            ->where('status_assignment', 'selesai')
            ->latest()
            ->take(5)
            ->get();

        return view('petugas.tugas.index', compact('tugasAktif', 'tugasSelesai'));
    }

    /**
     * Halaman riwayat tugas selesai (dengan pagination).
     */
    public function riwayat(Request $request)
    {
        $petugasId = auth()->user()->petugas?->id;
        abort_if(!$petugasId, 403, 'Akun Anda belum terdaftar sebagai petugas.');

        $tugasSelesai = Assignment::with(['pengaduan.kategori', 'pengaduan.zona', 'pengaduan.sla'])
            ->where('petugas_id', $petugasId)
            ->where('status_assignment', 'selesai')
            ->latest('tanggal_selesai')
            ->paginate(10);

        return view('petugas.tugas.riwayat', compact('tugasSelesai'));
    }

    public function show(Assignment $tugas)
    {
        // Pastikan hanya petugas yang ditugaskan yang bisa lihat
        abort_if($tugas->petugas_id !== auth()->user()->petugas?->id, 403);

        $tugas->load(['pengaduan.kategori', 'pengaduan.zona', 'pengaduan.pelapor', 'pengaduan.sla']);
        return view('petugas.tugas.show', compact('tugas'));
    }

    public function update(Request $request, Assignment $tugas)
    {
        abort_if($tugas->petugas_id !== auth()->user()->petugas?->id, 403);

        $request->validate([
            'status_assignment'  => 'required|in:diproses,selesai',
            'catatan_penanganan' => 'nullable|string|max:1000',
            'foto_hasil'         => [
                $request->status_assignment === 'selesai' ? 'required' : 'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:5120',
            ],
        ], [
            'status_assignment.required' => 'Status penanganan wajib dipilih.',
            'foto_hasil.required'        => 'Foto dokumentasi wajib diunggah saat menyelesaikan tugas.',
            'foto_hasil.image'           => 'File harus berupa gambar.',
            'foto_hasil.max'             => 'Ukuran foto maksimal 5MB.',
        ]);

        // Update status via service
        $tugas = $this->assignmentService->updateStatus($tugas, $request->only([
            'status_assignment', 'catatan_penanganan', 'foto_hasil',
        ]));

        // Cek dan update SLA
        $this->slaService->cekDanUpdate($tugas->pengaduan);

        // Tandai SLA terpenuhi jika selesai
        if ($request->status_assignment === 'selesai') {
            $this->slaService->tandaiTerpenuhi($tugas->pengaduan);

            // Notifikasi ke pelapor bahwa pengaduan selesai
            $this->notifikasiService->kirim(
                $tugas->pengaduan->pelapor,
                $tugas->pengaduan,
                'Pengaduan Selesai Ditangani ✅',
                "Pengaduan #{$tugas->pengaduan->nomor_tiket} telah selesai ditangani. Berikan penilaian Anda!"
            );

            // Notifikasi ke supervisor bahwa tugas selesai
            if ($tugas->supervisor) {
                $this->notifikasiService->kirim(
                    $tugas->supervisor,
                    $tugas->pengaduan,
                    'Tugas Selesai Dilaporkan 📋',
                    "Petugas telah menyelesaikan pengaduan #{$tugas->pengaduan->nomor_tiket}. Silakan review."
                );
            }

            return redirect()
                ->route('petugas.tugas.index')
                ->with('success', 'Pengaduan berhasil diselesaikan! Pelapor telah diberitahu.');
        }

        // Notifikasi update status diproses
        $this->notifikasiService->kirim(
            $tugas->pengaduan->pelapor,
            $tugas->pengaduan,
            'Pengaduan Sedang Diproses 🔧',
            "Pengaduan #{$tugas->pengaduan->nomor_tiket} sedang dalam proses penanganan oleh petugas kami."
        );

        return redirect()
            ->route('petugas.tugas.show', $tugas)
            ->with('success', 'Status berhasil diperbarui.');
    }
}
