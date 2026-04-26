<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';

    protected $fillable = [
        'user_id',
        'zona_id',
        'nama_pelanggan',
        'alamat',
        'nomor_sambungan',
        'no_telepon',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function zona()
    {
        // FIX BUG-11: pakai model Zona (canonical), bukan ZonaWilayah
        // Keduanya merujuk tabel zona_wilayah, tapi Zona adalah model utama.
        return $this->belongsTo(Zona::class, 'zona_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pengaduan()
    {
        return $this->hasMany(Pengaduan::class, 'user_id', 'user_id');
    }

    /** Pengaduan terbaru milik user yang sama (sinkron dengan gform). */
    public function latestPengaduan()
    {
        return $this->hasOne(Pengaduan::class, 'user_id', 'user_id')->latestOfMany();
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['zona_id'] ?? false, function ($query, $zona_id) {
            $query->where('zona_id', $zona_id);
        });

        $query->when(isset($filters['is_active']), function ($query) use ($filters) {
            $query->where('is_active', $filters['is_active']);
        });

        $query->when($filters['search'] ?? false, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('nama_pelanggan', 'like', "%{$search}%")
                      ->orWhere('nomor_sambungan', 'like', "%{$search}%")
                      ->orWhereHas('pengaduan', function ($q) use ($search) {
                          $q->where('nomor_tiket', 'like', "%{$search}%");
                      });
            });
        });
    }
}
