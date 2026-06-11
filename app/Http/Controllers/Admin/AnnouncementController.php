<?php

/**
 * CRUD Pengumuman Layanan oleh Admin
 * TANGGUNG JAWAB: Arthur Budi Maharesi
 * Sprint 1
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAnnouncementRequest;
use App\Http\Requests\Admin\UpdateAnnouncementRequest;
use App\Models\Announcement;
use App\Models\ZonaWilayah;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    /**
     * GET /admin/announcements
     * Daftar semua pengumuman dengan badge status Aktif / Kadaluarsa.
     */
    public function index(): View
    {
        $announcements = Announcement::withCount('zones')
            ->latest()
            ->paginate(15);

        return view('admin.announcements.index', compact('announcements'));
    }

    /**
     * GET /admin/announcements/create
     * Form buat pengumuman baru.
     */
    public function create(): View
    {
        $zonas = ZonaWilayah::where('is_active', true)->orderBy('nama_zona')->get();

        return view('admin.announcements.create', compact('zonas'));
    }

    /**
     * POST /admin/announcements
     * Simpan pengumuman baru + mapping zona.
     */
    public function store(StoreAnnouncementRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $zoneIds = $data['zone_ids'] ?? [];
        unset($data['zone_ids']);

        $data['is_active'] = $request->boolean('is_active', true);

        $announcement = Announcement::create($data);
        $announcement->zones()->sync($zoneIds);

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil dibuat.');
    }

    /**
     * GET /admin/announcements/{id}
     * Detail pengumuman + jumlah masyarakat terdampak.
     */
    public function show(int $id): View
    {
        $announcement = Announcement::with('zones.pelanggan')->findOrFail($id);

        $totalTerdampak = $announcement->zones->sum(fn ($z) => $z->pelanggan->count());

        return view('admin.announcements.show', compact('announcement', 'totalTerdampak'));
    }

    /**
     * GET /admin/announcements/{id}/edit
     * Form edit pengumuman.
     */
    public function edit(int $id): View
    {
        $announcement = Announcement::with('zones')->findOrFail($id);
        $zonas        = ZonaWilayah::where('is_active', true)->orderBy('nama_zona')->get();
        $selectedZoneIds = $announcement->zones->pluck('id')->toArray();

        return view('admin.announcements.edit', compact('announcement', 'zonas', 'selectedZoneIds'));
    }

    /**
     * PUT /admin/announcements/{id}
     * Update pengumuman + sinkronisasi zona.
     */
    public function update(UpdateAnnouncementRequest $request, int $id): RedirectResponse
    {
        $announcement = Announcement::findOrFail($id);

        $data = $request->validated();
        $zoneIds = $data['zone_ids'] ?? [];
        unset($data['zone_ids']);

        $data['is_active'] = $request->boolean('is_active', true);

        $announcement->update($data);
        $announcement->zones()->sync($zoneIds);

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    /**
     * DELETE /admin/announcements/{id}
     * Hapus pengumuman (cascade ke pivot).
     */
    public function destroy(int $id): RedirectResponse
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->zones()->detach();
        $announcement->delete();

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }
}
