<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Kategori — Jenis pengaduan + konfigurasi SLA default
 *
 * TANGGUNG JAWAB: Arthur Budi Maharesi (PBI 2)
 *
 * Contoh data:
 * - Air Keruh    → SLA 24 jam
 * - Air Bau      → SLA 24 jam
 * - Tidak Mengalir → SLA 12 jam
 * - Tekanan Lemah  → SLA 48 jam
 */
class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori_pengaduan';

    protected $fillable = [
        'nama_kategori',
        'kode_kategori',
        'deskripsi',
        'sla_jam',      // Batas waktu SLA dalam jam
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sla_jam'   => 'integer',
    ];

    public function pengaduans()
    {
        return $this->hasMany(Pengaduan::class);
    }
}
