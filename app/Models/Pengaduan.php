<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Model Pengaduan — Core model sistem SIGAP-AIR
 *
 * TANGGUNG JAWAB: Sanitra Savitri (PBI 4, 5, 6)
 *
 * Relasi (sesuai ERD):
 * - belongsTo User (pelapor)
 * - belongsTo Kategori
 * - belongsTo Zona
 * - hasOne Assignment
 * - hasOne Rating
 * - hasOne Sla
 */
class Pengaduan extends Model
{
    use HasFactory;

    protected $table = 'pengaduan';

    protected $fillable = [
        'nomor_tiket',
        'user_id',
        'kategori_id',
        'zona_id',
        'lokasi',
        'deskripsi',
        'foto_bukti',
        'status', // menunggu_verifikasi | disetujui | ditolak | ditugaskan | diproses | selesai
        'alasan_penolakan',
        'tanggal_pengajuan',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'datetime',
    ];

    // $appends DIHAPUS (FIX BUG-12):
    // tanggal_pengajuan adalah kolom DB nyata, bukan computed attribute.
    // Mendaftarkan kolom DB di $appends menyebabkan konflik dengan $casts:
    // Laravel mencoba memanggil accessor DAN cast secara bersamaan -> hasil tidak terduga.
    // $casts sudah cukup untuk serialize datetime ke JSON.

    // ========================
    // RELASI
    // ========================

    public function pelapor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kategori()
    {
        // FIX ERR-1: pakai KategoriPengaduan (PBI-02), bukan model Kategori lama
        return $this->belongsTo(KategoriPengaduan::class, 'kategori_id');
    }

    public function zona()
    {
        return $this->belongsTo(Zona::class);
    }

    public function assignment()
    {
        return $this->hasOne(Assignment::class);
    }

    public function rating()
    {
        return $this->hasOne(Rating::class);
    }

    public function sla()
    {
        return $this->hasOne(Sla::class);
    }

    public function statusLogs()
    {
        return $this->hasMany(StatusLog::class)->latest();
    }

    // ========================
    // SCOPES (Filter Query)
    // ========================

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeOverdue($query)
    {
        return $query->whereHas('sla', fn ($q) => $q->where('status_sla', 'overdue'));
    }

    // ========================
    // HELPER METHODS
    // ========================

    public static function generateNomorTiket(): string
    {
        // Format: SIGAP-YYYYMMDD-XXXX
        $prefix = 'SIGAP-' . now()->format('Ymd');
        $last = static::whereDate('created_at', today())->count() + 1;
        return $prefix . '-' . str_pad($last, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Kompatibilitas lintas-PBI:
     * beberapa bagian app memakai tanggal_pengajuan, sementara migrasi hanya created_at.
     */
    public function getTanggalPengajuanAttribute($value)
    {
        if (!empty($value)) {
            return Carbon::parse($value);
        }

        return $this->created_at;
    }
}
