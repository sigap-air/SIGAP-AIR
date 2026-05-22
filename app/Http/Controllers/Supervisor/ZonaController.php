<?php

/**
 * PBI-21 — Supervisor: Melihat Detail Zona Wilayah & Statistik Pengaduan
 * TANGGUNG JAWAB: Arthur Budi Maharesi
 * Sprint 1
 */

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\ZonaWilayah;
use Illuminate\View\View;

class ZonaController extends Controller
{
    /**
     * GET /supervisor/zona
     * Tampilkan daftar semua zona wilayah beserta statistik pengaduan per zona.
     * Supervisor hanya bisa melihat (read-only).
     */
    public function index(): View
    {
        $zonas = ZonaWilayah::withCount([
                'petugas',
                'pengaduan',
                // Aktif = status bukan selesai / ditolak
                'pengaduan as pengaduan_aktif_count' => fn ($q) => $q->whereNotIn('status', ['selesai', 'ditolak']),
                // Selesai
                'pengaduan as pengaduan_selesai_count' => fn ($q) => $q->where('status', 'selesai'),
                // Overdue — relasi sla dengan status_sla = 'overdue'
                'pengaduan as pengaduan_overdue_count' => fn ($q) => $q->whereHas('sla', fn ($s) => $s->where('status_sla', 'overdue')),
            ])
            ->orderBy('nama_zona')
            ->paginate(15);

        return view('supervisor.zona.index', compact('zonas'));
    }

    /**
     * GET /supervisor/zona/{id}
     * Detail zona + statistik mendalam + daftar petugas.
     * Supervisor hanya bisa melihat (read-only), tidak ada aksi edit/hapus/assign.
     */
    public function show(int $id): View
    {
        $zona = ZonaWilayah::findOrFail($id);
        $zona->load('petugas.user');

        // Hitung statistik pengaduan zona ini
        $stats = [
            'total'   => $zona->pengaduan()->count(),
            'aktif'   => $zona->pengaduan()->whereNotIn('status', ['selesai', 'ditolak'])->count(),
            'selesai' => $zona->pengaduan()->where('status', 'selesai')->count(),
            'overdue' => $zona->pengaduan()->whereHas('sla', fn ($q) => $q->where('status_sla', 'overdue'))->count(),
        ];

        // Beban kerja: kapasitas = petugas × 5, persentase pengaduan aktif
        $kapasitas = max(1, $zona->petugas->count() * 5);
        $bebanPersen = min(100, round(($stats['aktif'] / $kapasitas) * 100));

        // Pengaduan terbaru (5 terakhir) untuk preview di halaman detail
        $pengaduanTerbaru = $zona->pengaduan()
            ->with('sla')
            ->latest()
            ->limit(5)
            ->get();

        return view('supervisor.zona.show', compact('zona', 'stats', 'bebanPersen', 'kapasitas', 'pengaduanTerbaru'));
    }
}
