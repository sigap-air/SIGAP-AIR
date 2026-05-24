<?php

namespace App\Services;

use App\Models\Petugas;
use App\Models\ZonaWilayah;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * PBI-17 — Manajemen Petugas Teknis
 * Logika bisnis untuk daftar, kinerja, dan histori penugasan petugas.
 */
class PetugasManajemenService
{
    /**
     * Query daftar petugas dengan filter dan hitungan tugas.
     */
    public function queryIndex(Request $request)
    {
        $query = Petugas::with(['user', 'zona'])
            ->withCount([
                'assignments as total_selesai' => fn ($q) => $q->where('status_assignment', 'selesai'),
                'assignments as total_aktif'   => fn ($q) => $q->whereIn('status_assignment', ['ditugaskan', 'diproses']),
            ])
            ->join('users', 'petugas.user_id', '=', 'users.id')
            ->select('petugas.*')
            ->orderBy('users.name');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%")
                  ->orWhere('petugas.nip', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('petugas.status_tersedia', $request->status);
        }

        if ($request->filled('zona_id')) {
            $query->where('petugas.zona_id', $request->zona_id);
        }

        return $query;
    }

    /**
     * Data halaman index petugas.
     *
     * @return array{petugas: \Illuminate\Contracts\Pagination\LengthAwarePaginator, zonas: \Illuminate\Support\Collection, stats: array<string, int>}
     */
    public function indexData(Request $request): array
    {
        $petugas = $this->queryIndex($request)->paginate(15)->withQueryString();
        $zonas   = ZonaWilayah::where('is_active', true)->orderBy('nama_zona')->get();

        $stats = [
            'total'       => Petugas::count(),
            'tersedia'    => Petugas::where('status_tersedia', 'tersedia')->count(),
            'sibuk'       => Petugas::where('status_tersedia', 'sibuk')->count(),
            'tidak_aktif' => Petugas::where('status_tersedia', 'tidak_aktif')->count(),
        ];

        return compact('petugas', 'zonas', 'stats');
    }

    /**
     * Statistik kinerja satu petugas untuk halaman detail.
     *
     * @return array{
     *     total_ditangani: int,
     *     total_selesai: int,
     *     total_aktif: int,
     *     rata_waktu_jam: float|null,
     *     rata_rating: float|null
     * }
     */
    public function getKinerjaPetugas(Petugas $petugas): array
    {
        $assignments = $petugas->assignments()
            ->with('pengaduan.rating')
            ->get();

        $selesai = $assignments->where('status_assignment', 'selesai');
        $aktif   = $assignments->whereIn('status_assignment', ['ditugaskan', 'diproses']);

        $rataWaktu = $selesai
            ->filter(fn ($a) => $a->tanggal_selesai)
            ->map(fn ($a) => Carbon::parse($a->created_at)->diffInHours($a->tanggal_selesai))
            ->avg();

        $rataRating = $selesai
            ->map(fn ($a) => $a->pengaduan?->rating?->bintang)
            ->filter()
            ->avg();

        return [
            'total_ditangani' => $assignments->count(),
            'total_selesai'   => $selesai->count(),
            'total_aktif'     => $aktif->count(),
            'rata_waktu_jam'  => $rataWaktu ? round($rataWaktu, 1) : null,
            'rata_rating'     => $rataRating ? round($rataRating, 1) : null,
        ];
    }
}
