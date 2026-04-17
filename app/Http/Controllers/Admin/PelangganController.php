<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\Zona;
use App\Http\Requests\Admin\StorePelangganRequest;
use App\Http\Requests\Admin\UpdatePelangganRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['zona_id', 'is_active', 'search']);

        $pelanggans = Pelanggan::with('zona', 'user')
            ->filter($filters)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $zonas = Zona::all();

        return view('admin.pelanggan.index', compact('pelanggans', 'zonas', 'filters'));
    }

    public function create()
    {
        $zonas = Zona::all();
        return view('admin.pelanggan.create', compact('zonas'));
    }

    public function store(StorePelangganRequest $request)
    {
        DB::transaction(function () use ($request) {
            Pelanggan::create($request->validated());
        });

        return redirect()->route('admin.pelanggan.index')
            ->with('success', 'Data pelanggan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $pelanggan = Pelanggan::with(['zona', 'user', 'pengaduans'])->findOrFail($id);
        return view('admin.pelanggan.show', compact('pelanggan'));
    }

    public function edit($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $zonas = Zona::all();
        return view('admin.pelanggan.edit', compact('pelanggan', 'zonas'));
    }

    public function update(UpdatePelangganRequest $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        DB::transaction(function () use ($request, $pelanggan) {
            $pelanggan->update($request->validated());
            
            // Note: If is_active is missing from the request (e.g. checkbox unchecked), 
            // the form request validated() might not include it if boolean.
            // But we have 'boolean' rule.
            if (!$request->has('is_active')) {
                $pelanggan->update(['is_active' => false]);
            }
        });

        return redirect()->route('admin.pelanggan.index')
            ->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        // BUSINEES RULE WAJIB: 
        // Cek apakah pelanggan memiliki pengaduan dengan status bukan 'selesai' atau 'ditolak'
        $activePengaduanCount = $pelanggan->pengaduans()
            ->whereNotIn('status', ['selesai', 'ditolak'])
            ->count();

        if ($activePengaduanCount > 0) {
            return redirect()->back()
                ->with('error', "Pelanggan tidak dapat dihapus karena masih memiliki {$activePengaduanCount} pengaduan aktif.");
        }

        DB::transaction(function () use ($pelanggan) {
            $pelanggan->delete();
        });

        return redirect()->route('admin.pelanggan.index')
            ->with('success', 'Data pelanggan berhasil dihapus.');
    }
}
