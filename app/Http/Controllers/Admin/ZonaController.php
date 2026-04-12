<?php
/**
 * PBI-03 — Manajemen Zona Wilayah + Mapping Petugas
 * TANGGUNG JAWAB: Arthur Budi Maharesi
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Zona, Petugas};
use Illuminate\Http\Request;

class ZonaController extends Controller
{
    public function index()
    {
        $zonas = Zona::with('petugas.user')->paginate(10);
        return view('admin.zona.index', compact('zonas'));
    }

    public function create()
    {
        $semuaPetugas = Petugas::with('user')->get();
        return view('admin.zona.form', compact('semuaPetugas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_zona'   => 'required|string|max:255|unique:zonas,nama_zona',
            'deskripsi'   => 'nullable|string|max:500',
            'is_active'   => 'boolean',
            'petugas_ids' => 'array',
            'petugas_ids.*' => 'exists:petugas,id',
        ]);

        $zona = Zona::create([
            'nama_zona'  => $request->nama_zona,
            'deskripsi'  => $request->deskripsi,
            'is_active'  => $request->boolean('is_active', true),
        ]);

        if ($request->filled('petugas_ids')) {
            $zona->petugas()->sync($request->petugas_ids);
        }

        return redirect()
            ->route('admin.zona.index')
            ->with('success', 'Zona berhasil ditambahkan.');
    }

    public function edit(Zona $zona)
    {
        $zona->load('petugas');
        $semuaPetugas = Petugas::with('user')->get();
        return view('admin.zona.form', compact('zona', 'semuaPetugas'));
    }

    public function show(Zona $zona)
    {
        $zona->load('petugas.user');
        $semuaPetugas = Petugas::with('user')->get();
        return view('admin.zona.mapping', compact('zona', 'semuaPetugas'));
    }

    public function update(Request $request, Zona $zona)
    {
        $request->validate([
            'nama_zona'     => 'required|string|max:255|unique:zonas,nama_zona,' . $zona->id,
            'deskripsi'     => 'nullable|string|max:500',
            'is_active'     => 'boolean',
            'petugas_ids'   => 'array',
            'petugas_ids.*' => 'exists:petugas,id',
        ]);

        $zona->update([
            'nama_zona' => $request->nama_zona,
            'deskripsi' => $request->deskripsi,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $zona->petugas()->sync($request->petugas_ids ?? []);

        return redirect()
            ->route('admin.zona.index')
            ->with('success', 'Zona berhasil diperbarui.');
    }

    public function destroy(Zona $zona)
    {
        $zona->update(['is_active' => false]);
        return redirect()
            ->route('admin.zona.index')
            ->with('success', 'Zona berhasil dinonaktifkan.');
    }
}
