<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPengaduan extends Model
{
    use HasFactory;

    protected $table = 'kategori_pengaduan';

    protected $fillable = [
        'nama_kategori',
        'kode_kategori',
        'deskripsi',
        'sla_jam',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sla_jam'   => 'integer',
    ];

    // ========================
    // RELASI
    // ========================

    /**
     * Semua pengaduan dengan kategori ini.
     */
    public function pengaduan()
    {
        return $this->hasMany(Pengaduan::class, 'kategori_id');
    }

    /**
     * Pengaduan aktif (belum selesai/ditolak) dengan kategori ini.
     * Digunakan untuk business rule destroy().
     */
    public function pengaduanAktif()
    {
        return $this->hasMany(Pengaduan::class, 'kategori_id')
                    ->whereNotIn('status', ['selesai', 'ditolak']);
    }
}
