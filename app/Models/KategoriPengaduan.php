<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPengaduan extends Model
{
    use HasFactory;

    /** Kode kategori resmi yang ditampilkan di form pengaduan masyarakat. */
    public const KODE_MASYARAKAT = ['AMT-01', 'AK-01', 'AM-02', 'AB-03'];

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

    /**
     * Kategori aktif yang boleh dipilih masyarakat saat mengajukan pengaduan.
     */
    public function scopeUntukMasyarakat($query)
    {
        $kodes = implode("','", self::KODE_MASYARAKAT);

        return $query->where('is_active', true)
            ->whereIn('kode_kategori', self::KODE_MASYARAKAT)
            ->orderByRaw("FIELD(kode_kategori, '{$kodes}')");
    }
}
