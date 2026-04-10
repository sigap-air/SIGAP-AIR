<?php
/**
 * PBI-09 — Konfigurasi SLA per Kategori
 * Admin dapat melihat dan mengubah batas waktu SLA untuk setiap kategori pengaduan.
 * Controller berada di namespace Petugas tapi diakses oleh route admin.
 */
namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class SlaController extends Controller
{
    /**
     * Daftar semua kategori beserta konfigurasi SLA-nya.
     */
    public function index()
    {
        $kategoris = Kategori::orderBy('nama_kategori')->paginate(15);
        return view('admin.sla.index', compact('kategoris'));
    }

    /**
     * Form edit konfigurasi SLA kategori tertentu.
     */
    public function edit(Kategori $sla)
    {
        // Parameter route bernama 'sla' tapi model-nya Kategori
        return view('admin.sla.edit', ['kategori' => $sla]);
    }

    /**
     * Simpan perubahan konfigurasi SLA.
     */
    public function update(Request $request, Kategori $sla)
    {
        $request->validate([
            'sla_jam'    => 'required|integer|min:1|max:720', // maks 30 hari
            'is_active'  => 'boolean',
        ], [
            'sla_jam.required' => 'Batas waktu SLA wajib diisi.',
            'sla_jam.min'      => 'SLA minimal 1 jam.',
            'sla_jam.max'      => 'SLA maksimal 720 jam (30 hari).',
        ]);

        $sla->update([
            'sla_jam'   => $request->sla_jam,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.sla.index')
            ->with('success', "SLA kategori \"{$sla->nama_kategori}\" berhasil diperbarui menjadi {$request->sla_jam} jam.");
    }
}
