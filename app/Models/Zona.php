<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Zona — Wilayah layanan PDAM
 *
 * TANGGUNG JAWAB: Arthur Budi Maharesi (PBI 3)
 */
class Zona extends Model
{
    use HasFactory;

    protected $table = 'zona_wilayah';

    protected $fillable = [
        'nama_zona',
        'deskripsi',
        'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function petugas()
    {
        // Relasi many-to-many: zona bisa punya banyak petugas
        return $this->belongsToMany(Petugas::class, 'zona_petugas');
    }

    public function pengaduans()
    {
        return $this->hasMany(Pengaduan::class);
    }
}
