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

    public function index(Request $request)
    {
        $data = $this->manajemenService->indexData($request);

        return view('supervisor.petugas.index', array_merge($data, [
            'readOnly'     => true,
            'routePrefix'  => 'supervisor.petugas',
        ]));
    }

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
            'readOnly'    => true,
            'routePrefix' => 'supervisor.petugas',
        ]);
    }
}
