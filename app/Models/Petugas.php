<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Petugas — Data tambahan untuk user dengan role petugas
 *
 * TANGGUNG JAWAB: Farisha Huwaida Shofha (PBI 17)
 */
class Petugas extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'zona_id',
        'nip',
        'status_tersedia',
        'nomor_pegawai',
        'status_ketersediaan', // tersedia | sibuk | tidak_aktif
    ];

    protected $casts = [
        'status_tersedia' => 'string',
        'status_ketersediaan' => 'string',
    ];

    public function user()   { return $this->belongsTo(User::class); }
    public function zona()   { return $this->belongsTo(Zona::class, 'zona_id'); }
    public function zonas()  { return $this->belongsTo(Zona::class, 'zona_id'); } // Backward compatibility
    public function assignments() { return $this->hasMany(Assignment::class); }

    public function getNomorPegawaiAttribute(): ?string
    {
        return $this->attributes['nomor_pegawai'] ?? $this->attributes['nip'] ?? null;
    }

    public function getStatusKetersediaanAttribute(): ?string
    {
        return $this->attributes['status_ketersediaan'] ?? $this->attributes['status_tersedia'] ?? null;
    }
}
