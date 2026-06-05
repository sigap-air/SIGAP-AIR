<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Petugas;
use App\Services\PetugasManajemenService;
use Illuminate\Http\Request;

/**
 * PBI-17 — Manajemen Petugas Teknis (Supervisor, read-only)
 */
class ManajemenPetugasController extends Controller
{
    public function __construct(
        private PetugasManajemenService $manajemenService
    ) {}

    /**
     * Daftar petugas untuk Supervisor.
     * readOnly=true: sembunyikan tombol Tambah/Edit/Hapus, tapi Ubah Status tetap aktif.
     */
    public function index(Request $request)
    {
        $data = $this->manajemenService->indexData($request);

        return view('supervisor.petugas.index', array_merge($data, [
            'readOnly'    => true,           // sembunyikan Edit & Hapus
            'routePrefix' => 'supervisor.petugas',
        ]));
    }

    /**
     * Detail petugas untuk Supervisor.
     * Supervisor dapat melihat histori & mengubah status ketersediaan.
     */
    public function show(Petugas $petugas)
    {
        $petugas->load(['user', 'zona']);

        $histori = $petugas->assignments()
            ->with(['pengaduan.kategori', 'pengaduan.rating'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $kinerja = $this->manajemenService->getKinerjaPetugas($petugas);

        return view('supervisor.petugas.show', compact('petugas', 'histori', 'kinerja') + [
            'readOnly'    => true,           // sembunyikan tombol Edit Data
            'routePrefix' => 'supervisor.petugas',
        ]);
    }
}
