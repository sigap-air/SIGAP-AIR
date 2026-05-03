<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Services\KinerjaService;
use Illuminate\Http\Request;

class KinerjaPetugasController extends Controller
{
    public function __construct(private KinerjaService $kinerjaService) {}

    /**
     * Menampilkan halaman laporan kinerja petugas.
     */
    public function index(Request $request)
    {
        $data = $this->kinerjaService->getAll($request->only(['zona_id', 'status', 'dari', 'sampai']));

        return view('supervisor.laporan.kinerja', compact('data'));
    }

    /**
     * Export data kinerja petugas ke format Excel.
     */
    public function exportExcel(Request $request)
    {
        return $this->kinerjaService->exportExcel($request->only(['zona_id', 'status', 'dari', 'sampai']));
    }
}
