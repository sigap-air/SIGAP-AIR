<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Sla — Service Level Agreement per pengaduan
 *
 * TANGGUNG JAWAB: Falah Adhi Chandra (PBI 9)
 */
class Sla extends Model
{
    use HasFactory;

    protected $table = 'sla_pengaduan';

    protected $fillable = [
        'pengaduan_id',
        'batas_waktu',      // Kapan harus selesai
        'status_sla',       // berjalan | terpenuhi | overdue
        'is_flagged',
        'resolved_at',
    ];

    protected $casts = [
        'batas_waktu' => 'datetime',
        'resolved_at' => 'datetime',
        'is_flagged'  => 'boolean',
    ];

    public function pengaduan() { return $this->belongsTo(Pengaduan::class); }

    /**
     * Alias kompatibilitas agar bagian kode lama tetap bisa akses $sla->deadline.
     */
    public function getDeadlineAttribute()
    {
        return $this->batas_waktu;
    }
}
