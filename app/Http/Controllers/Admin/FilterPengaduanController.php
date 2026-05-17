<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Kategori, Petugas, Zona};
use App\Services\DashboardService;
use Illuminate\Http\Request;

class FilterPengaduanController extends Controller
{
    private const FILTER_KEYS = [
        'nomor_tiket', 'q', 'status', 'zona_id', 'kategori_id', 'petugas_id',
        'dari', 'sampai', 'overdue', 'sort', 'direction',
    ];z

    public function __construct(private DashboardService $dashboardService) {}

    public function index(Request $request)
    {
        $input = $request->only(self::FILTER_KEYS);
        if ($request->boolean('overdue')) {
            $input['overdue'] = true;
        }

        $query = $this->dashboardService->pengaduanFilteredQuery($input);
        $pengaduans = $query->paginate(15)->withQueryString();

        return view('admin.pengaduan.filter', $this->sharedViewData($pengaduans));
    }

    public function export(Request $request)
    {
        $input = $request->only(self::FILTER_KEYS);
        if ($request->boolean('overdue')) {
            $input['overdue'] = true;
        }

        $query = $this->dashboardService->pengaduanFilteredQuery($input);

        return $this->dashboardService->exportPengaduanFilteredCsv($query);
    }

    /**
     * @param  \Illuminate\Contracts\Pagination\LengthAwarePaginator  $pengaduans
     */
    private function sharedViewData($pengaduans): array
    {
        return [
            'pengaduans' => $pengaduans,
            'zonas' => Zona::where('is_active', true)->orderBy('nama_zona')->get(),
            'kategoris' => Kategori::where('is_active', true)->orderBy('nama_kategori')->get(),
            'statuses' => $this->dashboardService->getPengaduanStatusOptions(),
            'petugasList' => Petugas::with('user')->orderBy('id')->get(),
            'routeIndex' => 'admin.pengaduan.filter',
            'routeExport' => 'admin.pengaduan.filter.export',
        ];
    }
}
