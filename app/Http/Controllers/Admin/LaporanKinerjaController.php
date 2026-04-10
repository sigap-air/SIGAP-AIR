<?php
/**
 * PBI-18 — Laporan Kinerja Petugas + Export CSV
 * TANGGUNG JAWAB: Farisha Huwaida Shofha
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\KinerjaService;
use Illuminate\Http\Request;

class LaporanKinerjaController extends Controller
{
    public function __construct(private KinerjaService $kinerjaService) {}

    public function index(Request $request)
    {
        $data = $this->kinerjaService->getAll($request->only(['zona_id', 'status', 'dari', 'sampai']));
        return view('supervisor.laporan.kinerja', compact('data'));
    }

    public function exportExcel(Request $request)
    {
        return $this->kinerjaService->exportExcel($request->only(['zona_id', 'status', 'dari', 'sampai']));
    }
}
