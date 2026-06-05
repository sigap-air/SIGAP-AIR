<?php

namespace App\Services;

use App\Models\Petugas;
use Illuminate\Support\Collection;

/**
 * Monitoring status petugas untuk supervisor (sebelum & saat assignment).
 *
 * Status sama dengan admin: tersedia | sibuk | tidak_aktif
 */
class PetugasMonitoringService
{
    public const STATUS_TERSEDIA = 'tersedia';
    public const STATUS_SIBUK = 'sibuk';
    public const STATUS_TIDAK_AKTIF = 'tidak_aktif';

    /** @var list<string> */
    public const ACTIVE_ASSIGNMENT_STATUSES = ['ditugaskan', 'diproses'];

    /**
     * Jika petugas punya tugas aktif, paksa status Sibuk.
     * Tidak menimpa status manual (Sibuk/Tersedia) ketika tidak ada tugas aktif.
     */
    public function syncOperationalStatuses(?int $zonaId = null): void
    {
        $query = Petugas::query()->withCount([
            'assignments as tugas_aktif_count' => function ($q) {
                $q->whereIn('status_assignment', self::ACTIVE_ASSIGNMENT_STATUSES);
            },
        ]);

        if ($zonaId !== null) {
            $query->where(function ($q) use ($zonaId) {
                $q->where('zona_id', $zonaId)
                  ->orWhereHas('zones', function ($z) use ($zonaId) {
                      $z->where('zona_wilayah.id', $zonaId);
                  });
            });
        }

        foreach ($query->get() as $petugas) {
            if ($petugas->status_tersedia === self::STATUS_TIDAK_AKTIF) {
                continue;
            }

            $hasActive = ($petugas->tugas_aktif_count ?? 0) > 0;

            if ($hasActive && $petugas->status_tersedia !== self::STATUS_SIBUK) {
                $petugas->update(['status_tersedia' => self::STATUS_SIBUK]);
            }
        }
    }

    /**
     * Setelah semua tugas selesai, kembalikan petugas ke Tersedia (hanya petugas terkait).
     */
    public function releaseIfNoActiveAssignments(Petugas $petugas): void
    {
        if ($petugas->status_tersedia === self::STATUS_TIDAK_AKTIF) {
            return;
        }

        $activeCount = $petugas->assignments()
            ->whereIn('status_assignment', self::ACTIVE_ASSIGNMENT_STATUSES)
            ->count();

        if ($activeCount === 0 && $petugas->status_tersedia === self::STATUS_SIBUK) {
            $petugas->update(['status_tersedia' => self::STATUS_TERSEDIA]);
        }
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function getMonitorList(?int $zonaId = null): Collection
    {
        $query = Petugas::query()
            ->with(['user:id,name,email', 'zona:id,nama_zona,kode_zona'])
            ->withCount([
                'assignments as tugas_aktif_count' => function ($q) {
                    $q->whereIn('status_assignment', self::ACTIVE_ASSIGNMENT_STATUSES);
                },
            ])
            ->orderBy('zona_id')
            ->orderBy('id');

        if ($zonaId !== null) {
            $query->where(function ($q) use ($zonaId) {
                $q->where('zona_id', $zonaId)
                  ->orWhereHas('zones', function ($z) use ($zonaId) {
                      $z->where('zona_wilayah.id', $zonaId);
                  });
            });
        }

        return $query->get()->map(fn (Petugas $petugas) => $this->formatPetugasRow($petugas));
    }

    /**
     * @return array{tersedia: int, sibuk: int, tidak_aktif: int, total: int}
     */
    public function getSummary(?int $zonaId = null): array
    {
        $rows = $this->getMonitorList($zonaId);

        return [
            'tersedia'    => $rows->where('status_key', self::STATUS_TERSEDIA)->count(),
            'sibuk'       => $rows->where('status_key', self::STATUS_SIBUK)->count(),
            'tidak_aktif' => $rows->where('status_key', self::STATUS_TIDAK_AKTIF)->count(),
            'total'       => $rows->count(),
        ];
    }

    public function resolveStatusKey(Petugas $petugas): string
    {
        $status = $petugas->status_tersedia ?? self::STATUS_TIDAK_AKTIF;

        if (! in_array($status, [self::STATUS_TERSEDIA, self::STATUS_SIBUK, self::STATUS_TIDAK_AKTIF], true)) {
            return self::STATUS_TIDAK_AKTIF;
        }

        return $status;
    }

    public function isSelectableForAssignment(Petugas $petugas): bool
    {
        return $this->resolveStatusKey($petugas) === self::STATUS_TERSEDIA;
    }

    /**
     * @return array<string, string>
     */
    public static function statusMeta(string $statusKey): array
    {
        return match ($statusKey) {
            self::STATUS_SIBUK => [
                'label' => 'Sibuk',
                'badge' => 'bg-amber-50 text-amber-700',
                'dot'   => 'bg-amber-500',
            ],
            self::STATUS_TIDAK_AKTIF => [
                'label' => 'Tidak Aktif',
                'badge' => 'bg-gray-100 text-gray-600',
                'dot'   => 'bg-gray-400',
            ],
            default => [
                'label' => 'Tersedia',
                'badge' => 'bg-emerald-50 text-emerald-700',
                'dot'   => 'bg-emerald-500',
            ],
        };
    }

    /**
     * @return array<string, mixed>
     */
    private function formatPetugasRow(Petugas $petugas): array
    {
        $statusKey = $this->resolveStatusKey($petugas);
        $meta = self::statusMeta($statusKey);

        return [
            'id'                 => $petugas->id,
            'nama'               => $petugas->user?->name ?? '—',
            'nip'                => $petugas->nip ?? '—',
            'zona_id'            => $petugas->zona_id,
            'zona_nama'          => $petugas->zona?->nama_zona ?? 'Tanpa zona',
            'status_key'         => $statusKey,
            'status_label'       => $meta['label'],
            'status_badge'       => $meta['badge'],
            'status_dot'         => $meta['dot'],
            'status_tersedia'    => $petugas->status_tersedia,
            'tugas_aktif'        => (int) ($petugas->tugas_aktif_count ?? 0),
            'dapat_dipilih'      => $statusKey === self::STATUS_TERSEDIA,
        ];
    }
}
