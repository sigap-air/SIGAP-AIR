<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'content',
        'type',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_active'  => 'boolean',
    ];

    // ─── Labels ──────────────────────────────────────────────

    public static array $typeLabels = [
        'disruption'  => 'Gangguan',
        'maintenance' => 'Pemeliharaan',
        'info'        => 'Informasi Umum',
    ];

    public static array $typeColors = [
        'disruption'  => 'red',
        'maintenance' => 'amber',
        'info'        => 'blue',
    ];

    public function typeLabelText(): string
    {
        return self::$typeLabels[$this->type] ?? $this->type;
    }

    public function typeColor(): string
    {
        return self::$typeColors[$this->type] ?? 'gray';
    }

    // ─── Status Helpers ───────────────────────────────────────

    /**
     * Apakah pengumuman ini sedang aktif saat ini?
     * Aktif = is_active=true DAN start_date <= hari ini DAN end_date >= hari ini
     */
    public function isCurrentlyActive(): bool
    {
        return $this->is_active
            && $this->start_date->lte(now())
            && $this->end_date->gte(now()->startOfDay());
    }

    // ─── Scopes ──────────────────────────────────────────────

    /**
     * Scope: pengumuman yang sedang berlaku (tampil ke masyarakat).
     */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now()->startOfDay());
    }

    // ─── Relationships ────────────────────────────────────────

    /**
     * Zona wilayah yang terdampak oleh pengumuman ini.
     */
    public function zones(): BelongsToMany
    {
        return $this->belongsToMany(ZonaWilayah::class, 'announcement_zone', 'announcement_id', 'zone_id');
    }

    /**
     * Hitung total masyarakat yang terdampak berdasarkan zona.
     */
    public function totalMasyarakatTerdampak(): int
    {
        return $this->zones()
            ->withCount('pelanggan')
            ->get()
            ->sum('pelanggan_count');
    }
}
