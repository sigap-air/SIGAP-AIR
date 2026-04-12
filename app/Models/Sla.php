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

    protected $fillable = [
        'pengaduan_id',
        'deadline',         // Kapan harus selesai
        'is_overdue',       // Apakah sudah melewati deadline
        'is_fulfilled',     // Apakah SLA terpenuhi
        'waktu_selesai',
    ];

    protected $casts = [
        'deadline'      => 'datetime',
        'waktu_selesai' => 'datetime',
        'is_overdue'    => 'boolean',
        'is_fulfilled'  => 'boolean',
    ];

    public function pengaduan() { return $this->belongsTo(Pengaduan::class); }
}
