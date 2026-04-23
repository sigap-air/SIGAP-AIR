<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PengaduanFilterService;
use Illuminate\Http\Request;

class DaftarPengaduanController extends Controller
{
    public function __construct(private PengaduanFilterService $pengaduanFilterService) {}

    public function index(Request $request)
    {
        $pengaduans = $this->pengaduanFilterService
            ->filteredQuery($request)
            ->paginate(15)
            ->withQueryString();

        $dropdown = $this->pengaduanFilterService->dropdownData();

        return view('admin.pengaduan.daftar', [
            'pengaduans'     => $pengaduans,
            'zonas'          => $dropdown['zonas'],
            'kategoris'      => $dropdown['kategoris'],
            'statuses'       => $dropdown['statuses'],
            'petugasList'    => $dropdown['petugas'],
            'indexRoute'     => 'admin.pengaduan.index',
            'exportCsvRoute' => 'admin.pengaduan.export-csv',
            'pageTitle'      => 'Daftar & Filter Pengaduan',
        ]);
    }

    public function exportCsv(Request $request)
    {
        return $this->pengaduanFilterService->csvExportResponse($request);
    }
}
