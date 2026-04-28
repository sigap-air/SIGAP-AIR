<?php

namespace App\Services;

use App\Models\{Pengaduan, Assignment, Petugas, Zona, Kategori, Rating};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class LaporanService
{
    /**
     * Ambil data rekap pengaduan dengan filter opsional.
     */
    public function getRekap(array $filter = []): array
    {
        $tanggalKolom = Schema::hasColumn('pengaduan', 'tanggal_pengajuan')
            ? 'tanggal_pengajuan'
            : 'created_at';

        $query = Pengaduan::with(['kategori', 'zona', 'assignment', 'sla'])
            ->when(!empty($filter['dari']), fn($q) => $q->where($tanggalKolom, '>=', Carbon::parse($filter['dari'])->startOfDay()))
            ->when(!empty($filter['sampai']), fn($q) => $q->where($tanggalKolom, '<=', Carbon::parse($filter['sampai'])->endOfDay()))
            ->when(!empty($filter['zona_id']), fn($q) => $q->where('zona_id', $filter['zona_id']))
            ->when(!empty($filter['kategori_id']), fn($q) => $q->where('kategori_id', $filter['kategori_id']))
            ->when(!empty($filter['status']), fn($q) => $q->where('status', $filter['status']));

        $pengaduans = $query->latest()->get();

        // Distribusi per status
        $perStatus = $pengaduans->groupBy('status')->map->count();

        // Rata-rata waktu penanganan (jam) — hanya untuk yang selesai
        $rataWaktu = $pengaduans->filter(fn($p) => $p->status === 'selesai' && $p->assignment?->tanggal_selesai)
            ->map(function ($p) {
                $waktuMulai = $p->tanggal_pengajuan ?? $p->created_at;

                return Carbon::parse($waktuMulai)->diffInHours($p->assignment->tanggal_selesai);
            })
            ->avg();

        return [
            'pengaduans'     => $pengaduans,
            'total'          => $pengaduans->count(),
            'per_status'     => $perStatus,
            'rata_waktu_jam' => $rataWaktu ? round($rataWaktu, 1) : null,
            'total_overdue'  => $pengaduans->filter(fn($p) => $p->sla?->is_overdue)->count(),
            'filter'         => $filter,
            'zonas'          => Zona::where('is_active', true)->get(),
            'kategoris'      => Kategori::where('is_active', true)->get(),
        ];
    }

    /**
     * Data kinerja per petugas untuk laporan admin.
     */
    public function getKinerja(array $filter = []): array
    {
        $query = Petugas::with(['user', 'assignments.pengaduan.sla', 'assignments.pengaduan.rating'])
            ->when(!empty($filter['zona_id']), fn($q) => $q->whereHas('zonas', fn($z) => $z->where('zonas.id', $filter['zona_id'])))
            ->when(!empty($filter['status']), fn($q) => $q->where('status_ketersediaan', $filter['status']));

        $petugas = $query->get()->map(function ($p) use ($filter) {
            $assignments = $p->assignments
                ->when(!empty($filter['dari']), fn($c) => $c->filter(fn($a) => $a->created_at >= $filter['dari']))
                ->when(!empty($filter['sampai']), fn($c) => $c->filter(fn($a) => $a->created_at <= Carbon::parse($filter['sampai'])->endOfDay()));

            $selesai   = $assignments->where('status_assignment', 'selesai');
            $rataWaktu = $selesai->filter(fn($a) => $a->tanggal_selesai)
                ->map(fn($a) => Carbon::parse($a->created_at)->diffInHours($a->tanggal_selesai))
                ->avg();

            $rataRating = $selesai->map(fn($a) => $a->pengaduan?->rating?->bintang)
                ->filter()->avg();

            return [
                'petugas'        => $p,
                'total_tugas'    => $assignments->count(),
                'total_selesai'  => $selesai->count(),
                'rata_waktu_jam' => $rataWaktu ? round($rataWaktu, 1) : null,
                'rata_rating'    => $rataRating ? round($rataRating, 1) : null,
            ];
        });

        return [
            'kinerja'  => $petugas,
            'zonas'    => Zona::where('is_active', true)->get(),
            'filter'   => $filter,
        ];
    }
}
