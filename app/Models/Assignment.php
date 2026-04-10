<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Assignment — Penghubung pengaduan dan petugas teknis
 *
 * TANGGUNG JAWAB: Sanitra Savitri (PBI 6)
 */
class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengaduan_id',
        'petugas_id',
        'supervisor_id',
        'instruksi',
        'jadwal_penanganan',
        'status_assignment', // ditugaskan | diproses | selesai
        'catatan_penanganan',
        'foto_hasil',
        'tanggal_selesai',
    ];

    protected $casts = [
        'jadwal_penanganan' => 'datetime',
        'tanggal_selesai'   => 'datetime',
    ];

    public function pengaduan()  { return $this->belongsTo(Pengaduan::class); }
    public function petugas()    { return $this->belongsTo(Petugas::class); }
    public function supervisor() { return $this->belongsTo(User::class, 'supervisor_id'); }
}
