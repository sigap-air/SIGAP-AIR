<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Pelanggan — Data pelanggan PDAM terdaftar
 *
 * TANGGUNG JAWAB: Arthur Budi Maharesi (PBI 1)
 */
class Pelanggan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nomor_sambungan',  // Nomor ID pelanggan PDAM
        'alamat',
        'zona_id',
        'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function user() { return $this->belongsTo(User::class); }
    public function zona() { return $this->belongsTo(Zona::class); }
}
