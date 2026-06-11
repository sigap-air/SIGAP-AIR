<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Services\BebanPenangananService;
use Illuminate\Http\Request;

/**
 * PBI-36 — Monitoring Beban Penanganan Pengaduan
 *
 * Supervisor memantau jumlah pengaduan yang ditangani setiap petugas
 * agar distribusi penugasan dapat dilakukan secara lebih seimbang.
 *
 * TANGGUNG JAWAB: Farisha
 */
class BebanPenangananController extends Controller
{
    public function __construct(
        private BebanPenangananService $bebanService
    ) {}

    /**
     * Tampilkan halaman monitoring beban penanganan.
     */
    public function index(Request $request)
    {
        $data = $this->bebanService->getBebanData($request);

        return view('supervisor.beban-penanganan.index', $data);
    }
}
