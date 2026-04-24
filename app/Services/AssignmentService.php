<?php

namespace App\Services;

use App\Models\{Assignment, Pengaduan, Petugas, User};
use Illuminate\Support\Facades\DB;

class AssignmentService
{
    public function __construct(private NotifikasiService $notifikasiService) {}

    /**
     * Tugaskan petugas ke pengaduan yang sudah disetujui supervisor.
     */
    public function tugaskan(Pengaduan $pengaduan, array $data, User $supervisor): Assignment
    {
        return DB::transaction(function () use ($pengaduan, $data, $supervisor) {
            // 1. Buat record assignment
            $assignment = Assignment::create([
                'pengaduan_id'      => $pengaduan->id,
                'petugas_id'        => $data['petugas_id'],
                'supervisor_id'     => $supervisor->id,
                'instruksi'         => $data['instruksi'] ?? null,
                'jadwal_penanganan' => $data['jadwal_penanganan'],
                'status_assignment' => 'ditugaskan',
            ]);

            // 2. Update status pengaduan menjadi ditugaskan
            $statusLama = $pengaduan->status;
            $pengaduan->update(['status' => 'ditugaskan']);

            // 3. Log perubahan status
            $this->catatStatusLog($pengaduan, $supervisor, $statusLama, 'ditugaskan', 'Ditugaskan ke petugas.');

            // 4. Ambil data petugas dan user-nya untuk notifikasi
            $petugas = Petugas::with('user')->find($data['petugas_id']);

            // 5. Notifikasi ke petugas
            if ($petugas && $petugas->user) {
                $this->notifikasiService->kirim(
                    $petugas->user,
                    $pengaduan,
                    'Tugas Baru Ditugaskan',
                    "Anda mendapat tugas baru: pengaduan #{$pengaduan->nomor_tiket} di {$pengaduan->zona->nama_zona}."
                );
            }

            // 6. Notifikasi ke pelapor
            $this->notifikasiService->kirim(
                $pengaduan->pelapor,
                $pengaduan,
                'Petugas Sedang Dalam Perjalanan',
                "Pengaduan #{$pengaduan->nomor_tiket} telah ditugaskan ke petugas. Jadwal penanganan: "
                    . \Carbon\Carbon::parse($data['jadwal_penanganan'])->translatedFormat('d F Y, H:i') . ' WIB.'
            );

            return $assignment;
        });
    }

    /**
     * Update status penanganan oleh petugas (diproses / selesai).
     */
    public function updateStatus(Assignment $assignment, array $data): Assignment
    {
        return DB::transaction(function () use ($assignment, $data) {
            $fotoHasil = $assignment->foto_hasil;

            if (isset($data['foto_hasil'])) {
                $fotoHasil = $data['foto_hasil']->store('uploads/penanganan', 'public');
            }

            $statusLama = $assignment->status_assignment;

            $updateData = [
                'status_assignment'  => $data['status_assignment'],
                'catatan_penanganan' => $data['catatan_penanganan'] ?? $assignment->catatan_penanganan,
                'foto_hasil'         => $fotoHasil,
            ];

            if ($data['status_assignment'] === 'selesai') {
                $updateData['tanggal_selesai'] = now();
            }

            $assignment->update($updateData);

            // Sync status pengaduan — enum pengaduan: sedang_diproses | selesai
            $statusPengaduan = match ($data['status_assignment']) {
                'diproses' => 'sedang_diproses',
                'selesai'  => 'selesai',
                default    => $assignment->pengaduan->status,
            };

            $statusPengaduanLama = $assignment->pengaduan->status;
            $assignment->pengaduan->update(['status' => $statusPengaduan]);

            // Catat perubahan di status_log
            $this->catatStatusLog(
                $assignment->pengaduan,
                auth()->user(),
                $statusPengaduanLama,
                $statusPengaduan,
                $data['catatan_penanganan'] ?? null
            );

            return $assignment->fresh();
        });
    }

    /**
     * Catat perubahan status ke tabel status_log.
     */
    private function catatStatusLog(Pengaduan $pengaduan, User $user, ?string $statusLama, string $statusBaru, ?string $catatan = null): void
    {
        DB::table('status_log')->insert([
            'pengaduan_id' => $pengaduan->id,
            'user_id'      => $user->id,
            'status_lama'  => $statusLama,
            'status_baru'  => $statusBaru,
            'catatan'      => $catatan,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
    }
}
