<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Pelanggan extends Model
{
    use HasFactory;

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function zona()
    {
        // Adjusting ZonaWilayah request to match the likely existing Zona model
        return $this->belongsTo(Zona::class, 'zona_id');
    }

    public function pengaduans()
    {
        return $this->hasMany(Pengaduan::class, 'user_id', 'user_id');
    }

    public function scopeFilter(Builder $query, array $filters)
    {
        $query->when($filters['zona_id'] ?? false, function ($query, $zona) {
            $query->where('zona_id', $zona);
        });

        $query->when(isset($filters['is_active']), function ($query) use ($filters) {
            // allows '0' or '1' or actual booleans
            if ($filters['is_active'] !== '') {
                $query->where('is_active', $filters['is_active']);
            }
        });

        $query->when($filters['search'] ?? false, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelanggan', 'like', '%' . $search . '%')
                  ->orWhere('nomor_sambungan', 'like', '%' . $search . '%');
            });
        });
    }
}
