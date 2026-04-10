<?php
/**
 * PBI-14 — Laporan Rekap Pengaduan Periodik
 * Controller menggunakan LaporanService sebagai sumber data.
 * Export PDF menggunakan blade print-page CSS (tanpa dependency eksternal).
 */
namespace App\Http\Controllers\Supervisor;

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
        $data = $this->laporanService->getRekap(
            $request->only(['dari', 'sampai', 'zona_id', 'kategori_id', 'status'])
        );
        // Print-page view (browser print dialog)
        return view('supervisor.laporan.rekap-pdf', compact('data'));
    }
}
