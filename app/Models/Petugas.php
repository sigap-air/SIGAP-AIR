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
        'nomor_pegawai',
        'status_ketersediaan', // tersedia | sibuk | tidak_aktif
    ];

    public function user()   { return $this->belongsTo(User::class); }
    public function zonas()  { return $this->belongsToMany(Zona::class, 'zona_petugas'); }
    public function assignments() { return $this->hasMany(Assignment::class); }
}
