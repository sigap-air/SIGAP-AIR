<?php
/**
 * PBI-13 — Filter & Pencarian Lanjutan Pengaduan
 * Supervisor dan Admin memakai layanan yang sama (lihat Admin\DaftarPengaduanController).
 */
namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use App\Services\PengaduanFilterService;
use Illuminate\Http\Request;

class FilterPengaduanController extends Controller
{
    public function __construct(private PengaduanFilterService $pengaduanFilterService) {}

    public function index(Request $request)
    {
        $pengaduans = $this->pengaduanFilterService
            ->filteredQuery($request)
            ->paginate(15)
            ->withQueryString();

        $dropdown = $this->pengaduanFilterService->dropdownData();

        return view('supervisor.pengaduan.daftar', [
            'pengaduans'       => $pengaduans,
            'zonas'            => $dropdown['zonas'],
            'kategoris'        => $dropdown['kategoris'],
            'statuses'         => $dropdown['statuses'],
            'petugasList'      => $dropdown['petugas'],
            'indexRoute'       => 'supervisor.filter.index',
            'exportCsvRoute'   => 'supervisor.filter.export-csv',
            'pageTitle'        => 'Filter Pengaduan',
            'detailRouteName'  => 'supervisor.pengaduan.show',
            'showAksiEyeOnly'  => true,
        ]);
    }

    public function show(Pengaduan $pengaduan)
    {
        $pengaduan->load([
            'pelapor',
            'kategori',
            'zona',
            'sla',
            'assignment.petugas.user',
            'assignment.supervisor',
        ]);

        return view('supervisor.pengaduan.show', compact('pengaduan'));
    }

    public function exportCsv(Request $request)
    {
        return $this->pengaduanFilterService->csvExportResponse($request);
    }
}
