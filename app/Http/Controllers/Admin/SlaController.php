<?php
/**
 * PBI-09 — Konfigurasi SLA per Kategori Pengaduan
 * TANGGUNG JAWAB: Falah Adhi Chandra
 *
 * Fitur:
 * - Admin melihat daftar semua kategori + batas SLA masing-masing
 * - Admin mengubah batas waktu SLA (jam) per kategori
 * - Admin mengaktifkan / menonaktifkan kategori
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Sla;
use Illuminate\Http\Request;

class SlaController extends Controller
{
    /**
     * Daftar semua kategori beserta konfigurasi SLA-nya.
     */
    public function index()
    {
        $kategoris = Kategori::orderBy('nama_kategori')->paginate(15);

        // Statistik ringkas untuk dashboard SLA
        $stats = [
            'total_kategori'  => Kategori::count(),
            'kategori_aktif'  => Kategori::where('is_active', true)->count(),
            'rata_sla'        => (int) Kategori::where('is_active', true)->avg('sla_jam'),
            'sla_terpendek'   => Kategori::where('is_active', true)->min('sla_jam'),
            'sla_terpanjang'  => Kategori::where('is_active', true)->max('sla_jam'),
            'overdue_aktif'   => Sla::where('status_sla', 'overdue')->count(),
        ];

        return view('admin.sla.index', compact('kategoris', 'stats'));
    }

    /**
     * Form edit konfigurasi SLA kategori tertentu.
     */
    public function edit(Kategori $sla)
    {
        // Hitung statistik terkait kategori ini
        $pengaduanStats = [
            'total'     => $sla->pengaduans()->count(),
            'berjalan'  => $sla->pengaduans()->whereHas('sla', fn($q) => $q->where('status_sla', 'berjalan'))->count(),
            'overdue'   => $sla->pengaduans()->whereHas('sla', fn($q) => $q->where('status_sla', 'overdue'))->count(),
            'terpenuhi' => $sla->pengaduans()->whereHas('sla', fn($q) => $q->where('status_sla', 'terpenuhi'))->count(),
        ];

        return view('admin.sla.edit', ['kategori' => $sla, 'pengaduanStats' => $pengaduanStats]);
    }

    /**
     * Simpan perubahan konfigurasi SLA.
     */
    public function update(Request $request, Kategori $sla)
    {
        $request->validate([
            'sla_jam'   => 'required|integer|min:1|max:720',
            'is_active' => 'boolean',
        ], [
            'sla_jam.required' => 'Batas waktu SLA wajib diisi.',
            'sla_jam.min'      => 'SLA minimal 1 jam.',
            'sla_jam.max'      => 'SLA maksimal 720 jam (30 hari).',
        ]);

        $slaLama = $sla->sla_jam;

        $sla->update([
            'sla_jam'   => $request->sla_jam,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.sla.index')
            ->with('success', "SLA kategori \"{$sla->nama_kategori}\" berhasil diperbarui: {$slaLama} jam → {$request->sla_jam} jam.");
    }
}
