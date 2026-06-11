<?php
/**
 * PBI-14 — Laporan Rekap Pengaduan Periodik (Admin)
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LaporanService;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function __construct(private LaporanService $laporanService) {}

    public function index(Request $request)
    {
        $data = $this->laporanService->getRekap(
            $request->only(['dari', 'sampai', 'zona_id', 'kategori_id', 'status'])
        );

        return view('supervisor.laporan.rekap', compact('data'));
    }

    public function exportPdf(Request $request)
    {
        $filter = $request->only(['dari', 'sampai', 'zona_id', 'kategori_id', 'status']);
        $data = $this->laporanService->getRekap($filter);
        $filename = $this->laporanService->buildRekapExportFilename($filter);

        return view('supervisor.laporan.rekap-pdf', compact('data', 'filename'));
    }
}
