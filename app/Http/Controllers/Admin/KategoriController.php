<?php
/**
 * PBI-02 — Manajemen Kategori Pengaduan + SLA Default
 * TANGGUNG JAWAB: Arthur Budi Maharesi
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\StoreKategoriRequest;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::paginate(10);
        return view('admin.kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('admin.kategori.form');
    }

    public function store(StoreKategoriRequest $request)
    {
        // TODO ARTHUR: Implementasi create kategori
        Kategori::create($request->validated());
        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.form', compact('kategori'));
    }

    public function update(StoreKategoriRequest $request, Kategori $kategori)
    {
        // TODO ARTHUR: Update termasuk SLA jam
        $kategori->update($request->validated());
        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->update(['is_active' => false]);
        return redirect()->route('admin.kategori.index')->with('success', 'Kategori dinonaktifkan.');
    }
}
