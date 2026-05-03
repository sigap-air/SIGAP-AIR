<?php
/**
 * NotifikasiService — Kirim dan kelola notifikasi in-app
 * TANGGUNG JAWAB: Amanda Zuhra Azis (PBI 12)
 */
namespace App\Services;

use App\Models\{Notifikasi, User, Pengaduan, Assignment};

class NotifikasiService
{
    public function kirim(int $userId, ?int $pengaduanId, string $judul,
                          string $pesan, string $tipe): void
    {
        Notifikasi::create([
            'user_id'      => $userId,
            'pengaduan_id' => $pengaduanId,
            'judul'        => $judul,
            'pesan'        => $pesan,
            'tipe'         => $tipe,
        ]);
    }

    public function notifikasiPelapor(Pengaduan $pengaduan, string $judul,
                                      string $pesan, string $tipe): void
    {
        $this->kirim($pengaduan->user_id, $pengaduan->id, $judul, $pesan, $tipe);
    }

    public function notifikasiSupervisorPengaduanBaru(Pengaduan $pengaduan): void
    {
        User::where('role','supervisor')->where('is_active',true)
            ->each(function($sup) use ($pengaduan) {
                $this->kirim($sup->id, $pengaduan->id,
                    'Pengaduan Baru Masuk',
                    "Tiket {$pengaduan->nomor_tiket} menunggu verifikasi.",
                    'status_berubah');
            });
    }

    public function notifikasiAssignment(Pengaduan $pengaduan,
                                         Assignment $assignment): void
    {
        // Ke petugas
        $this->kirim($assignment->petugas->user_id, $pengaduan->id,
            'Penugasan Baru',
            "Anda ditugaskan menangani tiket {$pengaduan->nomor_tiket}.",
            'assignment');
        // Ke pelapor
        $this->notifikasiPelapor($pengaduan,
            'Pengaduan Sedang Ditangani',
            "Tiket {$pengaduan->nomor_tiket} telah ditugaskan ke petugas.",
            'status_berubah');
    }

    public function notifikasiSelesaiDanRating(Pengaduan $pengaduan): void
    {
        $this->notifikasiPelapor($pengaduan,
            'Pengaduan Selesai — Berikan Penilaian',
            "Tiket {$pengaduan->nomor_tiket} telah diselesaikan. Silakan beri penilaian.",
            'status_berubah');
    }

    public function notifikasiSupervisorOverdue(Pengaduan $pengaduan): void
    {
        User::where('role','supervisor')->where('is_active',true)
            ->each(function($sup) use ($pengaduan) {
                $this->kirim($sup->id, $pengaduan->id,
                    'SLA Overdue!',
                    "Tiket {$pengaduan->nomor_tiket} telah melewati batas waktu SLA.",
                    'overdue');
            });
    }
}
