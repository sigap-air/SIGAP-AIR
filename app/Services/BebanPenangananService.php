<?php

namespace App\Services;

use App\Models\Petugas;
use App\Models\ZonaWilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * PBI-36 — Monitoring Beban Penanganan Pengaduan
 *
 * Menyediakan data beban penanganan per petugas agar supervisor
 * dapat memantau distribusi tugas dan melakukan penyeimbangan beban.
 *
 * TANGGUNG JAWAB: Farisha
 */
class BebanPenangananService
{
    /**
     * Ambil data beban penanganan seluruh petugas.
     *
     * @return array{
     *     petugas: \Illuminate\Support\Collection,
     *     zonas: \Illuminate\Support\Collection,
     *     ringkasan: array<string, int|float>,
     *     filters: array<string, mixed>
     * }
     */
    public function getBebanData(Request $request): array
    {
        $zonas   = ZonaWilayah::where('is_active', true)->orderBy('nama_zona')->get();
        // Sort in PHP: MySQL disallows ORDER BY on withCount aliases when JOIN is present
        $petugas = $this->queryBeban($request)->get()
            ->sortByDesc('total_aktif')
            ->values();

        $ringkasan = $this->hitungRingkasan($petugas);

        return [
            'petugas'   => $petugas,
            'zonas'     => $zonas,
            'ringkasan' => $ringkasan,
            'filters'   => $request->only(['zona_id', 'status', 'search']),
        ];
    }

    /**
     * Query petugas beserta statistik beban penanganan.
     */
    public function queryBeban(Request $request)
    {
        $query = Petugas::query()
            ->with(['user:id,name,email', 'zona:id,nama_zona,kode_zona'])
            ->withCount([
                'assignments as total_ditangani',
                'assignments as total_aktif' => fn ($q) => $q->whereIn('status_assignment', ['ditugaskan', 'diproses']),
                'assignments as total_selesai' => fn ($q) => $q->where('status_assignment', 'selesai'),
            ])
            ->join('users', 'petugas.user_id', '=', 'users.id')
            ->select('petugas.*')
            ->orderBy('users.name');

        if ($request->filled('zona_id')) {
            $query->where('petugas.zona_id', $request->zona_id);
        }

        if ($request->filled('status')) {
            $query->where('petugas.status_tersedia', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('petugas.nip', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    /**
     * Hitung ringkasan statistik beban dari koleksi petugas.
     *
     * @param  Collection<int, Petugas>  $petugas
     * @return array{
     *     total_petugas: int,
     *     total_aktif: int,
     *     rata_beban: float,
     *     petugas_overload: int,
     *     petugas_idle: int
     * }
     */
    public function hitungRingkasan(Collection $petugas): array
    {
        $totalAktif   = (int) $petugas->sum('total_aktif');
        $totalPetugas = $petugas->count();

        return [
            'total_petugas'    => $totalPetugas,
            'total_aktif'      => $totalAktif,
            'rata_beban'       => $totalPetugas > 0
                                     ? round($totalAktif / $totalPetugas, 1)
                                     : 0,
            // Petugas overload: tugas aktif > 3
            'petugas_overload' => $petugas->filter(fn ($p) => (int) $p->total_aktif > 3)->count(),
            // Petugas idle: tersedia tapi tidak ada tugas aktif
            'petugas_idle'     => $petugas
                ->filter(fn ($p) => $p->status_tersedia === 'tersedia' && (int) $p->total_aktif === 0)
                ->count(),
        ];
    }

    /**
     * Tentukan label beban untuk tampilan badge.
     *
     * @return array{label: string, badge: string}
     */
    public static function bebanMeta(?int $totalAktif): array
    {
        $totalAktif = (int) $totalAktif;

        if ($totalAktif === 0) {
            return [
                'label' => 'Kosong',
                'badge' => 'bg-gray-100 text-gray-600',
            ];
        }

        if ($totalAktif <= 2) {
            return [
                'label' => 'Ringan',
                'badge' => 'bg-emerald-50 text-emerald-700',
            ];
        }

        if ($totalAktif <= 4) {
            return [
                'label' => 'Sedang',
                'badge' => 'bg-amber-50 text-amber-700',
            ];
        }

        return [
            'label' => 'Berat',
            'badge' => 'bg-rose-50 text-rose-700',
        ];
    }
}
