<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Notifikasi — Log notifikasi in-app SIGAP-AIR
 *
 * TANGGUNG JAWAB: Amanda Zuhra Azis (PBI 12)
 */
class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';

    protected $fillable = [
        'user_id',
        'pengaduan_id',
        'judul',
        'pesan',
        'tipe',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class);
    }
}
