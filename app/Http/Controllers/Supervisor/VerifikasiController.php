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
use App\Models\Pengaduan;
use App\Services\PengaduanService;
use Illuminate\Http\Request;

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
        return view('supervisor.verifikasi.show', compact('pengaduan'));
    }

    public function update(Request $request, Pengaduan $pengaduan)
    {
        $request->validate([
            'keputusan'        => 'required|in:disetujui,ditolak',
            'alasan_penolakan' => 'required_if:keputusan,ditolak|nullable|string',
        ]);

        // TODO SANITRA: Implementasi via PengaduanService + kirim notifikasi
        if ($request->keputusan === 'disetujui') {
            $this->pengaduanService->setujui($pengaduan, auth()->user());
            return redirect()->route('supervisor.assignment.create', $pengaduan)
                             ->with('success', 'Pengaduan disetujui. Silakan tugaskan petugas.');
        }

        $this->pengaduanService->tolak($pengaduan, $request->alasan_penolakan, auth()->user());
        return redirect()->route('supervisor.verifikasi.index')->with('success', 'Pengaduan ditolak.');
    }
}
