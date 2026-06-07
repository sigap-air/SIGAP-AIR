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

    /** Kode zona resmi yang ditampilkan di form pengaduan masyarakat. */
    public const KODE_MASYARAKAT = ['BDG-U01', 'BDG-S02', 'BDG-B03', 'BDG-T04'];

    // FIX: tabel aktual adalah zona_wilayah, bukan zonas (Laravel default)
    protected $table = 'zona_wilayah';

    protected $fillable = [
        'nama_zona',
        'kode_zona',
        'deskripsi',
        'geo_boundary',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'geo_boundary' => 'array',
    ];

    public function petugas()
    {
        // Relasi many-to-many: zona bisa punya banyak petugas
        return $this->belongsToMany(Petugas::class, 'officer_zone', 'zone_id', 'officer_id');
    }

    public function officers()
    {
        return $this->belongsToMany(Petugas::class, 'officer_zone', 'zone_id', 'officer_id');
    }

    public function pengaduans()
    {
        return $this->hasMany(Pengaduan::class);
    }

    /**
     * Zona aktif yang boleh dipilih masyarakat saat mengajukan pengaduan.
     */
    public function scopeUntukMasyarakat($query)
    {
        $kodes = implode("','", self::KODE_MASYARAKAT);

        return $query->where('is_active', true)
            ->whereIn('kode_zona', self::KODE_MASYARAKAT)
            ->orderByRaw("FIELD(kode_zona, '{$kodes}')");
    }
}
