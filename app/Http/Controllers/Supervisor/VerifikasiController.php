<?php
/**
 * PBI-05 — Verifikasi Pengaduan oleh Supervisor
 * TANGGUNG JAWAB: Sanitra Savitri
 *
 * Fitur:
 * - Antrean pengaduan menunggu verifikasi
 * - Tombol Approve / Tolak + input alasan
 * - Trigger notifikasi otomatis ke pelapor setelah keputusan
 */
namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Supervisor\VerifikasiPengaduanRequest;
use App\Models\Pengaduan;
use App\Services\PengaduanService;
use Illuminate\Http\RedirectResponse;

class VerifikasiController extends Controller
{
    public function __construct(private PengaduanService $pengaduanService) {}

    public function index()
    {
        $pengaduans = Pengaduan::with(['pelapor', 'kategori', 'zona'])
            ->byStatus('menunggu_verifikasi')
            ->latest()
            ->paginate(10);
        return view('supervisor.verifikasi.index', compact('pengaduans'));
    }

    public function show(Pengaduan $pengaduan)
    {
        $pengaduan->loadMissing(['pelapor', 'kategori', 'zona', 'sla']);

        return view('supervisor.verifikasi.show', compact('pengaduan'));
    }

    public function update(VerifikasiPengaduanRequest $request, Pengaduan $pengaduan): RedirectResponse
    {
        if ($pengaduan->status !== 'menunggu_verifikasi') {
            return redirect()
                ->route('supervisor.verifikasi.show', $pengaduan)
                ->with('error', 'Pengaduan ini sudah diverifikasi sebelumnya.');
        }

        $validated = $request->validated();

        if ($validated['keputusan'] === 'disetujui') {
            $this->pengaduanService->setujui($pengaduan, auth()->user());

            return redirect()
                ->route('supervisor.assignment.create', $pengaduan)
                ->with('success', 'Pengaduan disetujui. Silakan tugaskan petugas.');
        }

        $this->pengaduanService->tolak($pengaduan, $validated['alasan_penolakan'], auth()->user());

        return redirect()
            ->route('supervisor.verifikasi.index')
            ->with('success', 'Pengaduan ditolak dan notifikasi sudah dikirim ke pelapor.');
    }
}
