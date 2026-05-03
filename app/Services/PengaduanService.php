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

use App\Models\{Pelanggan, Pengaduan, Sla, User};
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PengaduanService
{
    public function __construct(private NotifikasiService $notifikasiService) {}

    /**
     * Buat pengaduan baru + generate tiket + set SLA
     *
     * @param  bool  $sinkronPelanggan  Jika true, data pelanggan di-update agar selaras dengan form publik.
     *                                Set false ketika pelanggan sudah dibuat admin (mis. dari Panel Pelanggan).
     */
    public function buat(array $data, User $pelapor, bool $sinkronPelanggan = true): Pengaduan
    {
        return DB::transaction(function () use ($data, $pelapor, $sinkronPelanggan) {
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

            // Simpan no telepon terbaru pelapor dari form pengaduan.
            $pelapor->update([
                'no_telepon' => $data['no_telepon'],
            ]);

            if ($sinkronPelanggan) {
                // Sinkronisasi ke data pelanggan admin agar input selaras dengan form masyarakat.
                Pelanggan::updateOrCreate(
                    ['user_id' => $pelapor->id],
                    [
                        'zona_id' => $data['zona_id'],
                        'nama_pelanggan' => $pelapor->name,
                        'alamat' => $data['lokasi'],
                        'nomor_sambungan' => 'AUTO-' . str_pad((string) $pelapor->id, 6, '0', STR_PAD_LEFT),
                        'no_telepon' => $data['no_telepon'],
                        'is_active' => true,
                    ]
                );
            }

            // 3. Set SLA otomatis berdasarkan kategori
            $slaJam = $pengaduan->kategori->sla_jam;
            Sla::create([
                'pengaduan_id' => $pengaduan->id,
                'batas_waktu'  => now()->addHours($slaJam),
                'status_sla'   => 'berjalan',
                'is_flagged'   => false,
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
        DB::transaction(function () use ($pengaduan, $supervisor) {
            $this->pastikanMenungguVerifikasi($pengaduan);

            $statusLama = $pengaduan->status;

            $pengaduan->update([
                'status' => 'disetujui',
                'alasan_penolakan' => null,
            ]);

            $this->catatStatusLog($pengaduan, $supervisor, $statusLama, 'disetujui', 'Pengaduan disetujui supervisor.');

            $this->notifikasiService->kirim(
                $pengaduan->pelapor,
                $pengaduan,
                'Pengaduan Disetujui',
                "Pengaduan #{$pengaduan->nomor_tiket} telah disetujui dan sedang dicari petugas yang tepat."
            );
        });
    }

    /**
     * Tolak pengaduan (PBI-05)
     */
    public function tolak(Pengaduan $pengaduan, string $alasan, User $supervisor): void
    {
        DB::transaction(function () use ($pengaduan, $alasan, $supervisor) {
            $this->pastikanMenungguVerifikasi($pengaduan);

            $statusLama = $pengaduan->status;

            $pengaduan->update([
                'status' => 'ditolak',
                'alasan_penolakan' => $alasan,
            ]);

            $this->catatStatusLog($pengaduan, $supervisor, $statusLama, 'ditolak', $alasan);

            $this->notifikasiService->kirim(
                $pengaduan->pelapor,
                $pengaduan,
                'Pengaduan Ditolak',
                "Pengaduan #{$pengaduan->nomor_tiket} ditolak. Alasan: {$alasan}"
            );
        });
    }

    private function pastikanMenungguVerifikasi(Pengaduan $pengaduan): void
    {
        if ($pengaduan->status !== 'menunggu_verifikasi') {
            throw ValidationException::withMessages([
                'status' => 'Pengaduan ini sudah diverifikasi dan tidak bisa diproses ulang.',
            ]);
        }
    }

    private function catatStatusLog(Pengaduan $pengaduan, User $user, ?string $statusLama, string $statusBaru, ?string $catatan = null): void
    {
        DB::table('status_log')->insert([
            'pengaduan_id' => $pengaduan->id,
            'user_id' => $user->id,
            'status_lama' => $statusLama,
            'status_baru' => $statusBaru,
            'catatan' => $catatan,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
