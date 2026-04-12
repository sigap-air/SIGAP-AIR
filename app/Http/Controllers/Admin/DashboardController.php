<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $dashboardService) {}

    public function index()
    {
        $kpi         = $this->dashboardService->getKpi();
        $adminStats  = $this->dashboardService->getAdminStats();
        $perKategori = $this->dashboardService->getPerKategori();
        $trenBulanan = $this->dashboardService->getTrenBulanan();

        return view('admin.dashboard', compact('kpi', 'adminStats', 'perKategori', 'trenBulanan'));
    }
}
