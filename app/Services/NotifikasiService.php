<?php
/**
 * NotifikasiService — Kirim dan kelola notifikasi in-app
 * TANGGUNG JAWAB: Amanda Zuhra Azis (PBI 12)
 */
namespace App\Services;

use App\Models\{Notifikasi, User, Pengaduan};

class NotifikasiService
{
    public function kirim(User $user, Pengaduan $pengaduan, string $judul, string $pesan): Notifikasi
    {
        return Notifikasi::create([
            'user_id'      => $user->id,
            'pengaduan_id' => $pengaduan->id,
            'judul'        => $judul,
            'pesan'        => $pesan,
            'is_read'      => false,
        ]);
    }

    public function jumlahBelumDibaca(User $user): int
    {
        return Notifikasi::where('user_id', $user->id)->where('is_read', false)->count();
    }
}
