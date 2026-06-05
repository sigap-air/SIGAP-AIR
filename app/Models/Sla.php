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
        'deadline',
        'is_overdue',
        'is_fulfilled',
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

    public function setDeadlineAttribute($value)
    {
        $this->attributes['batas_waktu'] = $value;
    }

    public function setIsOverdueAttribute($value)
    {
        if ($value) {
            $this->attributes['status_sla'] = 'overdue';
        } else if (($this->attributes['status_sla'] ?? null) === 'overdue') {
            $this->attributes['status_sla'] = 'berjalan';
        }
    }

    public function setIsFulfilledAttribute($value)
    {
        if ($value) {
            $this->attributes['status_sla'] = 'terpenuhi';
        } else if (($this->attributes['status_sla'] ?? null) === 'terpenuhi') {
            $this->attributes['status_sla'] = 'berjalan';
        }
    }

    /**
     * Accessor: apakah SLA sudah terlampaui (overdue)?
     * Digunakan di view petugas untuk indikator visual.
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->status_sla === 'overdue';
    }

    /**
     * Accessor: apakah SLA sudah terpenuhi?
     * Digunakan di SlaService->cekDanUpdate().
     */
    public function getIsFulfilledAttribute(): bool
    {
        return $this->status_sla === 'terpenuhi';
    }
}
