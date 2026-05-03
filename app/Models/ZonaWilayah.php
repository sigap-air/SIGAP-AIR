<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model ZonaWilayah — Wilayah layanan PDAM
 *
 * PBI-03 — Zona Wilayah & Pemetaan Petugas
 * TANGGUNG JAWAB: Arthur Budi Maharesi
 *
 * Catatan arsitektur:
 * Model Zona (app/Models/Zona.php) adalah alias/wrapper lama yang
 * masih direferensikan oleh model-model lain (Pelanggan, Pengaduan).
 * ZonaWilayah adalah canonical model PBI-03 — keduanya memakai tabel
 * zona_wilayah yang sama.
 */
class ZonaWilayah extends Model
{
    use HasFactory;

    protected $table = 'zona_wilayah';

    protected $fillable = [
        'nama_zona',
        'kode_zona',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ========================
    // RELASI
    // ========================

    /**
     * Petugas yang ditempatkan di zona ini (FK zona_id pada tabel petugas).
     */
    public function petugas()
    {
        return $this->hasMany(Petugas::class, 'zona_id');
    }

    /**
     * Pelanggan yang terdaftar di zona ini.
     */
    public function pelanggan()
    {
        return $this->hasMany(Pelanggan::class, 'zona_id');
    }

    /**
     * Pengaduan yang masuk ke zona ini.
     */
    public function pengaduan()
    {
        return $this->hasMany(Pengaduan::class, 'zona_id');
    }
}
