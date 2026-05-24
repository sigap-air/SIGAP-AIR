<?php
/**
 * PBI-15 — Dashboard Statistik Pengaduan Real-Time
 * TANGGUNG JAWAB: Imanuel Karmelio V. Liuw
 *
 * Fitur:
 * - Widget KPI: total masuk, sedang diproses, selesai, overdue
 * - Grafik pengaduan per kategori (bar chart)
 * - Grafik per zona wilayah
 * - Tren bulanan (line chart)
 */
namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use App\Services\DashboardService;
use App\Services\PetugasMonitoringService;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService,
        private PetugasMonitoringService $petugasMonitoringService,
    ) {}

    public function index()
    {
        $stats = $this->dashboardService->getAllStats();
        $kpi         = $stats['kpi'];
        $perKategori = $stats['per_kategori'];
        $perZona     = $stats['per_zona'];
        $trenBulanan = $stats['tren_bulanan'];
        $antrean    = Pengaduan::byStatus('menunggu_verifikasi')->latest()->take(10)->get();
        $petugasMonitorList = $this->petugasMonitoringService->getMonitorList();
        $petugasMonitorSummary = $this->petugasMonitoringService->getSummary();

        return view('supervisor.dashboard', compact(
            'kpi',
            'perKategori',
            'perZona',
            'trenBulanan',
            'antrean',
            'petugasMonitorList',
            'petugasMonitorSummary',
        ));
    }

    /** JSON endpoint untuk refresh KPI & grafik tanpa reload halaman */
    public function stats()
    {
        return response()->json($this->dashboardService->getAllStats());
    }
}
