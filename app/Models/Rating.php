<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Rating — Penilaian kepuasan pelanggan setelah pengaduan selesai
 *
 * TANGGUNG JAWAB: Amanda Zuhra Azis (PBI 11)
 */
class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengaduan_id',
        'user_id',
        'bintang',      // 1 - 5
        'komentar',
        'tanggal_rating',
    ];

    protected $casts = [
        'bintang'       => 'integer',
        'tanggal_rating' => 'datetime',
    ];

    public function pengaduan() { return $this->belongsTo(Pengaduan::class); }
    public function user()      { return $this->belongsTo(User::class); }
}
