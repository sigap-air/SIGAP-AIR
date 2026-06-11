<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Supervisor\AssignPetugasToPengaduanRequest;
use App\Models\{Pengaduan, Petugas};
use App\Services\{AssignmentService, PetugasManajemenService, PetugasMonitoringService};
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * PBI-17 — Manajemen Petugas Teknis (Supervisor, read-only)
 */
class ManajemenPetugasController extends Controller
{
    public function __construct(
        private PetugasManajemenService $manajemenService,
        private PetugasMonitoringService $monitoringService,
        private AssignmentService $assignmentService,
    ) {}

    /**
     * Daftar petugas untuk Supervisor.
     * readOnly=true: sembunyikan tombol Tambah/Edit/Hapus, tapi Ubah Status tetap aktif.
     */
    public function index(Request $request)
    {
        $data = $this->manajemenService->indexData($request);

        return view('supervisor.petugas.index', array_merge($data, [
            'readOnly'    => true,           // sembunyikan Edit & Hapus
            'routePrefix' => 'supervisor.petugas',
            'readOnly'          => true,
            'routePrefix'       => 'supervisor.petugas',
            'showCatatanInfo'   => true,
            'readOnly'        => true,
            'routePrefix'     => 'supervisor.petugas',
            'showCatatanInfo' => true,
        ]));
    }

    /**
     * Detail petugas untuk Supervisor.
     * Supervisor dapat melihat histori & mengubah status ketersediaan.
     */
    public function show(Petugas $petugas)
    {
        $petugas->load(['user', 'zona']);

        $histori = $petugas->assignments()
            ->with(['pengaduan.kategori', 'pengaduan.rating', 'supervisor'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $kinerja = $this->manajemenService->getKinerjaPetugas($petugas);

        $pengaduanMenungguTugas = $this->pengaduanSiapDitugaskanUntukPetugas($petugas);

        return view('supervisor.petugas.show', compact(
            'petugas',
            'histori',
            'kinerja',
            'pengaduanMenungguTugas',
        ) + [
            'readOnly'    => true,
            'routePrefix' => 'supervisor.petugas',
        ]);
    }

    /**
     * Tugaskan petugas dari halaman Data Petugas (dengan catatan assignment).
     */
    public function assign(AssignPetugasToPengaduanRequest $request, Petugas $petugas)
    {
        $data = $request->validated();
        $pengaduan = Pengaduan::findOrFail($data['pengaduan_id']);

        if ($pengaduan->assignment) {
            throw ValidationException::withMessages([
                'pengaduan_id' => 'Pengaduan ini sudah memiliki petugas yang ditugaskan.',
            ]);
        }

        if ($pengaduan->status !== 'disetujui') {
            throw ValidationException::withMessages([
                'pengaduan_id' => 'Pengaduan belum disetujui atau sudah diproses.',
            ]);
        }

        if (! $petugas->zona_id || $petugas->zona_id !== $pengaduan->zona_id) {
            throw ValidationException::withMessages([
                'pengaduan_id' => 'Petugas dan pengaduan harus berada di zona yang sama.',
            ]);
        }

        $this->monitoringService->syncOperationalStatuses($petugas->zona_id);

        if (! $this->monitoringService->isSelectableForAssignment($petugas->fresh())) {
            throw ValidationException::withMessages([
                'pengaduan_id' => 'Petugas tidak berstatus Tersedia. Hanya petugas Tersedia yang dapat ditugaskan.',
            ]);
        }

        $this->assignmentService->tugaskan($pengaduan, [
            'petugas_id'        => $petugas->id,
            'instruksi'         => $data['instruksi'] ?? null,
            'jadwal_penanganan' => $data['jadwal_penanganan'],
        ], auth()->user());

        return redirect()
            ->route('supervisor.petugas.show', $petugas)
            ->with('success', 'Petugas berhasil ditugaskan. Catatan assignment telah dikirim ke petugas.');
    }

    /**
     * @return \Illuminate\Support\Collection<int, Pengaduan>
     */
    private function pengaduanSiapDitugaskanUntukPetugas(Petugas $petugas)
    {
        if (! $petugas->zona_id || $petugas->status_tersedia !== 'tersedia') {
            return collect();
        }

        return Pengaduan::query()
            ->where('status', 'disetujui')
            ->where('zona_id', $petugas->zona_id)
            ->whereDoesntHave('assignment')
            ->with('kategori')
            ->latest('tanggal_pengajuan')
            ->get();
    }

    /** JSON endpoint untuk polling status petugas (dashboard & assignment). */
    public function status(Request $request): JsonResponse
    {
        $zonaId = $request->filled('zona_id') ? (int) $request->zona_id : null;
        $this->monitoringService->syncOperationalStatuses($zonaId);
        $petugasList = $this->monitoringService->getMonitorList($zonaId);

        return response()->json([
            'updated_at' => now()->toIso8601String(),
            'summary'    => $this->monitoringService->getSummary($zonaId),
            'petugas'    => $petugasList->values(),
        ]);
    }
}
