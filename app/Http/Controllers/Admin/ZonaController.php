<?php

/**
 * PBI-03 — Manajemen Zona Wilayah & Pemetaan Petugas
 * TANGGUNG JAWAB: Arthur Budi Maharesi
 * Sprint 1, Pekan 8
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreZonaRequest;
use App\Http\Requests\Admin\UpdateZonaRequest;
use App\Models\Petugas;
use App\Models\ZonaWilayah;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ZonaController extends Controller
{
    // ========================
    // CRUD: Zona Wilayah
    // ========================

    /**
     * GET /admin/zona
     * Tampilkan daftar semua zona wilayah dengan jumlah petugas & pengaduan.
     */
    public function index(): View
    {
        $zonas = ZonaWilayah::withCount('petugas', 'pengaduan')
            ->paginate(15);

        return view('admin.zona.index', compact('zonas'));
    }

    /**
     * GET /admin/zona/create
     * Tampilkan form pembuatan zona baru.
     */
    public function create(): View
    {
        return view('admin.zona.create');
    }

    /**
     * POST /admin/zona
     * Simpan zona baru ke database.
     */
    public function store(StoreZonaRequest $request): RedirectResponse
    {
        ZonaWilayah::create([
            'nama_zona' => $request->nama_zona,
            'kode_zona' => strtoupper($request->kode_zona),
            'deskripsi' => $request->deskripsi,
            'is_active'  => $request->boolean('is_active', true),
            'geo_boundary' => $request->geo_boundary ? json_decode($request->geo_boundary, true) : null,
        ]);

        return redirect()
            ->route('admin.zona.index')
            ->with('success', 'Zona wilayah berhasil ditambahkan.');
    }

    /**
     * GET /admin/zona/{id}
     * Detail zona + daftar petugas yang sudah & belum dipetakan.
     */
    public function show(int $id): View
    {
        $zona = ZonaWilayah::findOrFail($id);
        $zona->load('petugas.user');

        // Petugas yang belum dipetakan ke zona ini (tersedia untuk di-assign)
        $petugasTanpaZona = Petugas::whereDoesntHave('zones', function ($query) use ($zona) {
            $query->where('zona_wilayah.id', $zona->id);
        })
        ->with('user')
        ->get();

        return view('admin.zona.show', compact('zona', 'petugasTanpaZona'));
    }

    /**
     * GET /admin/zona/{id}/edit
     * Form edit zona yang sudah ada.
     */
    public function edit(int $id): View
    {
        $zona = ZonaWilayah::findOrFail($id);

        return view('admin.zona.edit', compact('zona'));
    }

    /**
     * PUT /admin/zona/{id}
     * Update data zona wilayah.
     */
    public function update(UpdateZonaRequest $request, int $id): RedirectResponse
    {
        $zona = ZonaWilayah::findOrFail($id);

        $zona->update([
            'nama_zona' => $request->nama_zona,
            'kode_zona' => strtoupper($request->kode_zona),
            'deskripsi' => $request->deskripsi,
            'is_active'  => $request->boolean('is_active', true),
            'geo_boundary' => $request->geo_boundary ? json_decode($request->geo_boundary, true) : null,
        ]);

        return redirect()
            ->route('admin.zona.show', $zona->id)
            ->with('success', 'Zona wilayah berhasil diperbarui.');
    }

    /**
     * DELETE /admin/zona/{id}
     * Hapus zona — diblokir jika masih ada pengaduan aktif.
     *
     * BUSINESS RULE: Zona tidak boleh dihapus jika ada pengaduan
     * dengan status selain 'selesai' atau 'ditolak'.
     */
    public function destroy(int $id): RedirectResponse
    {
        $zona = ZonaWilayah::findOrFail($id);

        $aktif = $zona->pengaduan()
            ->whereNotIn('status', ['selesai', 'ditolak'])
            ->count();

        if ($aktif > 0) {
            return redirect()->back()->with(
                'error',
                "Zona tidak dapat dihapus karena masih ada {$aktif} pengaduan aktif."
            );
        }

        $zona->delete();

        return redirect()
            ->route('admin.zona.index')
            ->with('success', 'Zona wilayah berhasil dihapus.');
    }

    // ========================
    // PEMETAAN PETUGAS
    // ========================

    /**
     * POST /admin/zona/{id}/assign-petugas
     * Petakan seorang petugas ke zona ini.
     */
    public function assignPetugas(\Illuminate\Http\Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'petugas_id' => ['required'],
        ]);

        $zona = ZonaWilayah::findOrFail($id);
        $petugasIds = is_array($request->petugas_id) ? $request->petugas_id : [$request->petugas_id];

        // Validasi keberadaan petugas
        foreach ($petugasIds as $petId) {
            Petugas::findOrFail($petId);
        }

        // Petakan ke tabel pivot
        $zona->petugas()->syncWithoutDetaching($petugasIds);

        // Update fallback zona_id ke database petugas (untuk backward compatibility)
        foreach ($petugasIds as $petId) {
            $p = Petugas::find($petId);
            if ($p && $p->zona_id === null) {
                $p->update(['zona_id' => $zona->id]);
            }
        }

        return redirect()->back()
            ->with('success', 'Petugas berhasil dipetakan ke zona.');
    }

    /**
     * DELETE /admin/zona/{id}/remove-petugas/{petugasId}
     * Lepaskan petugas dari zona.
     *
     * BUSINESS RULE: Tidak boleh dilepas jika masih ada assignment aktif di zona ini.
     */
    public function removePetugas(int $id, int $petugasId): RedirectResponse
    {
        // Pastikan zona benar-benar ada
        $zona = ZonaWilayah::findOrFail($id);

        $petugas = Petugas::findOrFail($petugasId);

        // Cek assignment aktif (ditugaskan | sedang_diproses) untuk pengaduan di zona ini
        if ($petugas->assignmentsAktif()->whereHas('pengaduan', fn($q) => $q->where('zona_id', $id))->exists()) {
            return redirect()->back()->with(
                'error',
                'Petugas tidak dapat dilepas karena masih memiliki tugas aktif di zona ini.'
            );
        }

        // Lepaskan relasi pivot many-to-many
        $zona->petugas()->detach($petugasId);

        // Update fallback zona_id pada tabel petugas ke zona lainnya jika masih ada
        $firstZone = $petugas->zones()->first();
        $petugas->update(['zona_id' => $firstZone ? $firstZone->id : null]);

        return redirect()->back()
            ->with('success', 'Petugas berhasil dilepas dari zona.');
    }
}
