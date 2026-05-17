<?php

namespace App\Services;

use App\Models\Petugas;
use Illuminate\Support\Collection;

/**
 * Monitoring status petugas untuk supervisor (sebelum & saat assignment).
 *
 * Label UI: Available | On-Duty | Off
 * DB: tersedia | sibuk | tidak_aktif
 */
class PetugasMonitoringService
{
    public const STATUS_AVAILABLE = 'available';
    public const STATUS_ON_DUTY = 'on_duty';
    public const STATUS_OFF = 'off';

    /** @var list<string> */
    public const ACTIVE_ASSIGNMENT_STATUSES = ['ditugaskan', 'diproses'];

    /**
     * Sinkronkan status_tersedia berdasarkan assignment aktif.
     */
    public function syncOperationalStatuses(?int $zonaId = null): void
    {
        $query = Petugas::query()->withCount([
            'assignments as tugas_aktif_count' => function ($q) {
                $q->whereIn('status_assignment', self::ACTIVE_ASSIGNMENT_STATUSES);
            },
        ]);

        if ($zonaId !== null) {
            $query->where('zona_id', $zonaId);
        }

        foreach ($query->get() as $petugas) {
            if ($petugas->status_tersedia === 'tidak_aktif') {
                continue;
            }

            $hasActive = ($petugas->tugas_aktif_count ?? 0) > 0;
            $target = $hasActive ? 'sibuk' : 'tersedia';

            if ($petugas->status_tersedia !== $target) {
                $petugas->update(['status_tersedia' => $target]);
            }
        }
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function getMonitorList(?int $zonaId = null): Collection
    {
        $this->syncOperationalStatuses($zonaId);

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
            $query->where('zona_id', $zonaId);
        }

        return $query->get()->map(fn (Petugas $petugas) => $this->formatPetugasRow($petugas));
    }

    /**
     * @return array{available: int, on_duty: int, off: int, total: int}
     */
    public function getSummary(?int $zonaId = null): array
    {
        $rows = $this->getMonitorList($zonaId);

        return [
            'available' => $rows->where('status_key', self::STATUS_AVAILABLE)->count(),
            'on_duty'   => $rows->where('status_key', self::STATUS_ON_DUTY)->count(),
            'off'       => $rows->where('status_key', self::STATUS_OFF)->count(),
            'total'     => $rows->count(),
        ];
    }

    public function resolveStatusKey(Petugas $petugas): string
    {
        if ($petugas->status_tersedia === 'tidak_aktif') {
            return self::STATUS_OFF;
        }

        $activeCount = $petugas->tugas_aktif_count
            ?? $petugas->assignments()
                ->whereIn('status_assignment', self::ACTIVE_ASSIGNMENT_STATUSES)
                ->count();

        if ($activeCount > 0 || $petugas->status_tersedia === 'sibuk') {
            return self::STATUS_ON_DUTY;
        }

        return self::STATUS_AVAILABLE;
    }

    public function isSelectableForAssignment(Petugas $petugas): bool
    {
        return $this->resolveStatusKey($petugas) === self::STATUS_AVAILABLE;
    }

    /**
     * @return array<string, string>
     */
    public static function statusMeta(string $statusKey): array
    {
        return match ($statusKey) {
            self::STATUS_ON_DUTY => [
                'label' => 'On-Duty',
                'badge' => 'bg-amber-50 text-amber-800 border-amber-200',
                'dot'   => 'bg-amber-500',
            ],
            self::STATUS_OFF => [
                'label' => 'Off',
                'badge' => 'bg-gray-100 text-gray-600 border-gray-200',
                'dot'   => 'bg-gray-400',
            ],
            default => [
                'label' => 'Available',
                'badge' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
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
            'dapat_dipilih'      => $statusKey === self::STATUS_AVAILABLE,
        ];
    }
}
