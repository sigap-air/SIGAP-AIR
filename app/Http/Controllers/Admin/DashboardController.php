<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $dashboardService) {}

    public function index()
    {
        $stats = $this->dashboardService->getAllStats();
        $kpi         = $stats['kpi'];
        $perKategori = $stats['per_kategori'];
        $perZona     = $stats['per_zona'];
        $trenBulanan = $stats['tren_bulanan'];
        $adminStats  = $this->dashboardService->getAdminStats();
        $pengaduanTerbaru = Pengaduan::with(['pelapor', 'kategori', 'zona'])
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dashboard', compact(
            'kpi',
            'adminStats',
            'perKategori',
            'perZona',
            'trenBulanan',
            'pengaduanTerbaru',
        ));
    }

    /** JSON endpoint untuk refresh KPI & grafik tanpa reload halaman */
    public function stats()
    {
        return response()->json($this->dashboardService->getAllStats());
    }
}
