<?php
/**
 * DashboardService — Agregasi data untuk dashboard statistik
 * TANGGUNG JAWAB: Imanuel Karmelio V. Liuw (PBI 13)
 */
namespace App\Services;

use App\Models\{Pengaduan, User, Petugas};
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardService
{
    /** Widget KPI: total masuk, menunggu, diproses, selesai, overdue */
    public function getKpi(): array
    {
        // Kompatibilitas lintas migrasi: ada branch yang memakai
        // status_ketersediaan, ada yang status_tersedia.
        $statusPetugasColumn = DB::getSchemaBuilder()->hasColumn('petugas', 'status_ketersediaan')
            ? 'status_ketersediaan'
            : 'status_tersedia';

        return [
            'total_masuk'         => Pengaduan::count(),
            'menunggu_verifikasi' => Pengaduan::byStatus('menunggu_verifikasi')->count(),
            'diproses'            => Pengaduan::whereIn('status', ['diproses', 'sedang_diproses'])->count(),
            'selesai'             => Pengaduan::byStatus('selesai')->count(),
            'overdue'             => Pengaduan::overdue()->count(),
            'total_petugas'       => Petugas::where($statusPetugasColumn, 'tersedia')->count(),
        ];
    }

    /** Data untuk bar chart per kategori */
    public function getPerKategori(): array
    {
        return Pengaduan::select('kategori_id', DB::raw('count(*) as total'))
            ->with('kategori:id,nama_kategori')
            ->groupBy('kategori_id')
            ->get()
            ->map(fn($row) => [
                'label' => $row->kategori->nama_kategori ?? 'Lainnya',
                'count' => $row->total,
            ])
            ->toArray();
    }

    /** Data untuk chart per zona wilayah */
    public function getPerZona(): array
    {
        return Pengaduan::select('zona_id', DB::raw('count(*) as total'))
            ->with('zona:id,nama_zona')
            ->groupBy('zona_id')
            ->get()
            ->map(fn($row) => [
                'label' => $row->zona->nama_zona ?? 'Tidak Diketahui',
                'count' => $row->total,
            ])
            ->toArray();
    }

    /** Data tren bulanan untuk line chart (12 bulan terakhir) */
    public function getTrenBulanan(): array
    {
        $months = collect(range(11, 0))->map(fn($i) => Carbon::now()->subMonths($i));

        return $months->map(function ($month) {
            return [
                'label' => $month->translatedFormat('M Y'),
                'count' => Pengaduan::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
            ];
        })->toArray();
    }

    /** Statistik khusus untuk dashboard admin */
    public function getAdminStats(): array
    {
        return [
            'total_user'      => User::count(),
            'per_role'        => User::select('role', DB::raw('count(*) as total'))->groupBy('role')->pluck('total', 'role'),
            'pengaduan_hari'  => Pengaduan::whereDate('created_at', today())->count(),
            'pengaduan_bulan' => Pengaduan::whereMonth('created_at', now()->month)->count(),
        ];
    }
}
