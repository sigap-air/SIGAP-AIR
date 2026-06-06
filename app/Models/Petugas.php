<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Petugas — Data tambahan untuk user dengan role petugas
 *
 * PBI-03 — Zona Wilayah & Pemetaan Petugas
 * TANGGUNG JAWAB: Arthur Budi Maharesi
 *
 * Kolom DB (sesuai migration 000003):
 *   user_id, zona_id (nullable setelah fix migration), nip, status_tersedia
 */
class Petugas extends Model
{
    use HasFactory;

    protected $table = 'petugas';

    protected $fillable = [
        'user_id',
        'zona_id',
        'nip',
        'status_tersedia',
    ];

    protected $casts = [
        'zona_id' => 'integer',
    ];

    // ========================
    // RELASI
    // ========================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Zona wilayah tempat petugas ditempatkan.
     * Menggunakan ZonaWilayah (canonical PBI-03 model).
     */
    public function zona()
    {
        return $this->belongsTo(ZonaWilayah::class, 'zona_id');
    }

    /**
     * Zona-zona wilayah tempat petugas ditempatkan (Many-to-Many).
     */
    public function zones()
    {
        return $this->belongsToMany(ZonaWilayah::class, 'officer_zone', 'officer_id', 'zone_id');
    }

    /**
     * Alias untuk zones() agar kompatibel dengan PBI06 Dusk Test.
     */
    public function zonas()
    {
        return $this->belongsToMany(ZonaWilayah::class, 'officer_zone', 'officer_id', 'zone_id');
    }

    /**
     * Semua assignment yang pernah diberikan kepada petugas ini.
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'petugas_id');
    }

    /**
     * Assignment yang masih aktif (belum selesai).
     * Digunakan untuk cek sebelum remove petugas dari zona.
     */
    public function assignmentsAktif()
    {
        return $this->hasMany(Assignment::class, 'petugas_id')
            ->whereIn('status_assignment', ['ditugaskan', 'diproses']);
    }

    public function isOffDuty(): bool
    {
        return $this->status_tersedia === 'tidak_aktif';
    }

    public function isAvailableForAssignment(): bool
    {
        return $this->status_tersedia === 'tersedia'
            && $this->assignmentsAktif()->count() === 0;
    }
}

