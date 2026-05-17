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

    // tanggal_pengajuan tidak dimasukkan ke $appends agar cast datetime tetap bekerja
    // Accessor di bawah hanya sebagai fallback ke created_at jika kolom null

    // ========================
    // ROUTE KEY
    // ========================

    /**
     * PBI-10: route model binding pakai nomor_tiket bukan id.
     * Contoh: /riwayat/SIGAP-20250503-0001
     */
    public function getRouteKeyName(): string
    {
        return 'nomor_tiket';
    }


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

    /**
     * PBI-10: Log perubahan status pengaduan untuk tampilan timeline.
     * Model StatusLog belum dibuat — fallback ke penggunaan created_at / updated_at.
     */
    public function statusLog()
    {
        // Jika model StatusLog sudah dibuat, gunakan:
        // return $this->hasMany(StatusLog::class)->orderBy('created_at');
        return $this->hasMany(Assignment::class); // placeholder sampai model dibuat

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
     * Selalu mengembalikan Carbon instance agar bisa dipanggil ->timezone(), ->format(), dll.
     */
    public function getTanggalPengajuanAttribute(): \Illuminate\Support\Carbon
    {
        $raw = $this->attributes['tanggal_pengajuan'] ?? null;
        if ($raw) {
            return \Illuminate\Support\Carbon::parse($raw);
        }
        return $this->created_at ?? now();

    }
}
