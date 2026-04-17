<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model User — Pengguna sistem SIGAP-AIR
 *
 * TANGGUNG JAWAB: Farisha Huwaida Shofha (PBI 16)
 *
 * Role yang tersedia: admin | supervisor | petugas | masyarakat
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'role',         // admin | supervisor | petugas | masyarakat
        'no_telepon',
        'foto_profil',
        'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_active'         => 'boolean',
    ];

    // ========================
    // ROLE HELPERS
    // ========================

    public function isAdmin(): bool      { return $this->role === 'admin'; }
    public function isSupervisor(): bool { return $this->role === 'supervisor'; }
    public function isPetugas(): bool    { return $this->role === 'petugas'; }
    public function isMasyarakat(): bool { return $this->role === 'masyarakat'; }

    public function dashboardPath(): string
    {
        return match ($this->role) {
            'admin' => '/admin/dashboard',
            'supervisor' => '/supervisor/dashboard',
            'petugas' => '/petugas/dashboard',
            default => '/masyarakat/dashboard',
        };
    }

    // ========================
    // RELASI
    // ========================

    public function pengaduans()
    {
        return $this->hasMany(Pengaduan::class);
    }

    public function petugas()
    {
        return $this->hasOne(Petugas::class);
    }

    public function notifikasis()
    {
        // TODO AMANDA (PBI-12): setup relasi notifikasi
        return $this->hasMany(Notifikasi::class);
    }
}
