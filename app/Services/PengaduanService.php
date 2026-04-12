<?php
/**
 * PengaduanService — Business logic pengaduan
 * TANGGUNG JAWAB: Sanitra Savitri (PBI 4, 5, 6)
 *
 * Mengandung logic yang TIDAK boleh ada di controller:
 * - Generate nomor tiket
 * - Simpan foto ke storage
 * - Set SLA otomatis berdasarkan kategori
 * - Kirim notifikasi
 */
namespace App\Services;

use App\Models\{Pengaduan, Sla, User};
use App\Notifications\{PengaduanDiterimaNotification, PengaduanDisetujuiNotification, PengaduanDitolakNotification};
use Illuminate\Support\Facades\{DB, Storage};

class PengaduanService
{
    public function __construct(private NotifikasiService $notifikasiService) {}

    /**
     * Buat pengaduan baru + generate tiket + set SLA
     */
    public function buat(array $data, User $pelapor): Pengaduan
    {
        return DB::transaction(function () use ($data, $pelapor) {
            // 1. Upload foto bukti
            $fotoBukti = null;
            if (isset($data['foto_bukti'])) {
                $fotoBukti = $data['foto_bukti']->store('uploads/pengaduan', 'public');
            }

            // 2. Buat pengaduan
            $pengaduan = Pengaduan::create([
                'nomor_tiket'       => Pengaduan::generateNomorTiket(),
                'user_id'           => $pelapor->id,
                'kategori_id'       => $data['kategori_id'],
                'zona_id'           => $data['zona_id'],
                'lokasi'            => $data['lokasi'],
                'deskripsi'         => $data['deskripsi'],
                'foto_bukti'        => $fotoBukti,
                'status'            => 'menunggu_verifikasi',
                'tanggal_pengajuan' => now(),
            ]);

            // 3. Set SLA otomatis berdasarkan kategori
            $slaJam = $pengaduan->kategori->sla_jam;
            Sla::create([
                'pengaduan_id' => $pengaduan->id,
                'deadline'     => now()->addHours($slaJam),
                'is_overdue'   => false,
                'is_fulfilled' => false,
            ]);

            // 4. Kirim notifikasi ke pelapor
            $this->notifikasiService->kirim(
                $pelapor,
                $pengaduan,
                'Pengaduan Diterima',
                "Nomor tiket {$pengaduan->nomor_tiket} telah diterima dan sedang menunggu verifikasi."
            );

            return $pengaduan;
        });
    }

    /**
     * Setujui pengaduan (PBI-05)
     */
    public function setujui(Pengaduan $pengaduan, User $supervisor): void
    {
        $pengaduan->update(['status' => 'disetujui']);
        $this->notifikasiService->kirim(
            $pengaduan->pelapor,
            $pengaduan,
            'Pengaduan Disetujui',
            "Pengaduan #{$pengaduan->nomor_tiket} telah disetujui dan sedang dicari petugas yang tepat."
        );
    }

    /**
     * Tolak pengaduan (PBI-05)
     */
    public function tolak(Pengaduan $pengaduan, string $alasan, User $supervisor): void
    {
        $pengaduan->update(['status' => 'ditolak', 'alasan_penolakan' => $alasan]);
        $this->notifikasiService->kirim(
            $pengaduan->pelapor,
            $pengaduan,
            'Pengaduan Ditolak',
            "Pengaduan #{$pengaduan->nomor_tiket} ditolak. Alasan: {$alasan}"
        );
    }
}
