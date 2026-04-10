<?php
/**
 * PBI-01 — Manajemen Data Pelanggan PDAM
 * TANGGUNG JAWAB: Arthur Budi Maharesi
 *
 * Fitur:
 * - CRUD data pelanggan (nama, alamat, nomor sambungan, zona, status aktif)
 * - Validasi nomor sambungan unik
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\StorePelangganRequest;
use App\Models\{Pelanggan, Zona, User};
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggans = Pelanggan::with(['user', 'zona'])->paginate(15);
        return view('admin.pelanggan.index', compact('pelanggans'));
    }

    public function create()
    {
        $zonas = Zona::where('is_active', true)->get();
        return view('admin.pelanggan.create', compact('zonas'));
    }

    public function store(StorePelangganRequest $request)
    {
        // TODO ARTHUR: Implementasi create pelanggan + auto-create user
        Pelanggan::create($request->validated());
        return redirect()->route('admin.pelanggan.index')->with('success', 'Data pelanggan berhasil ditambahkan.');
    }

    public function edit(Pelanggan $pelanggan)
    {
        $zonas = Zona::where('is_active', true)->get();
        return view('admin.pelanggan.edit', compact('pelanggan', 'zonas'));
    }

    public function update(StorePelangganRequest $request, Pelanggan $pelanggan)
    {
        // TODO ARTHUR: Implementasi update pelanggan
        $pelanggan->update($request->validated());
        return redirect()->route('admin.pelanggan.index')->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        // TODO ARTHUR: Soft delete atau nonaktifkan, jangan hard delete
        $pelanggan->update(['is_active' => false]);
        return redirect()->route('admin.pelanggan.index')->with('success', 'Pelanggan berhasil dinonaktifkan.');
    }
}
