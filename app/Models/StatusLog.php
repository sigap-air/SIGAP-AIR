<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model StatusLog — Riwayat perubahan status pengaduan
 *
 * TANGGUNG JAWAB: Falah Adhi Chandra (PBI-07)
 *
 * Setiap kali status pengaduan berubah (ditugaskan → diproses → selesai),
 * satu record StatusLog dicatat untuk audit trail dan timeline tracking.
 */
class StatusLog extends Model
{
    use HasFactory;

    protected $table = 'status_log';

    protected $fillable = [
        'pengaduan_id',
        'user_id',
        'status_lama',
        'status_baru',
        'catatan',
    ];

    // ========================
    // RELASI
    // ========================

    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ========================
    // HELPER
    // ========================

    /**
     * Label status yang human-readable untuk ditampilkan di timeline.
     */
    public function getLabelStatusBaruAttribute(): string
    {
        return match ($this->status_baru) {
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'disetujui'          => 'Disetujui',
            'ditolak'            => 'Ditolak',
            'ditugaskan'         => 'Ditugaskan ke Petugas',
            'sedang_diproses'    => 'Sedang Diproses',
            'selesai'            => 'Selesai',
            default              => ucwords(str_replace('_', ' ', $this->status_baru)),
        };
    }

    /**
     * Ikon Material Symbol sesuai status untuk UI timeline.
     */
    public function getIconAttribute(): string
    {
        return match ($this->status_baru) {
            'menunggu_verifikasi' => 'hourglass_top',
            'disetujui'          => 'verified',
            'ditolak'            => 'cancel',
            'ditugaskan'         => 'assignment_ind',
            'sedang_diproses'    => 'construction',
            'selesai'            => 'check_circle',
            default              => 'info',
        };
    }

    /**
     * Warna CSS class sesuai status untuk UI timeline.
     */
    public function getColorClassAttribute(): string
    {
        return match ($this->status_baru) {
            'menunggu_verifikasi' => 'text-gray-500 bg-gray-100',
            'disetujui'          => 'text-sky-600 bg-sky-100',
            'ditolak'            => 'text-red-600 bg-red-100',
            'ditugaskan'         => 'text-amber-600 bg-amber-100',
            'sedang_diproses'    => 'text-indigo-600 bg-indigo-100',
            'selesai'            => 'text-emerald-600 bg-emerald-100',
            default              => 'text-gray-500 bg-gray-100',
        };
    }
}
