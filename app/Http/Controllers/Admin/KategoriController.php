<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreKategoriRequest;
use App\Http\Requests\Admin\UpdateKategoriRequest;
use App\Models\KategoriPengaduan;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = KategoriPengaduan::withCount(['pengaduan', 'pengaduanAktif'])
            ->orderBy('nama_kategori')
            ->paginate(15);

        return view('admin.kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(StoreKategoriRequest $request)
    {
        KategoriPengaduan::create($request->validated());

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori pengaduan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kategori = KategoriPengaduan::findOrFail($id);

        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(UpdateKategoriRequest $request, $id)
    {
        $kategori = KategoriPengaduan::findOrFail($id);

        // BUSINESS RULE: perubahan sla_jam HANYA berlaku untuk pengaduan BARU.
        // SLA yang sudah berjalan di tabel sla_pengaduan TIDAK diubah.
        // Cukup update record kategori; record sla_pengaduan yang sudah ada
        // menyimpan deadline-nya sendiri dan tidak tersentuh oleh perubahan ini.
        $data = $request->validated();
        if (!$request->has('is_active')) {
            $data['is_active'] = false;
        }

        $kategori->update($data);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori pengaduan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kategori = KategoriPengaduan::findOrFail($id);

        // BUSINESS RULE: tolak hapus jika masih ada pengaduan aktif
        $aktif = $kategori->pengaduanAktif()->count();

        if ($aktif > 0) {
            return redirect()->back()
                ->with('error', "Kategori tidak dapat dihapus karena masih digunakan oleh {$aktif} pengaduan aktif.");
        }

        $kategori->delete();

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori pengaduan berhasil dihapus.');
    }
}
