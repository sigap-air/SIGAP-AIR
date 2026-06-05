<?php

namespace App\Services;

use App\Models\{Assignment, Pengaduan, Petugas, StatusLog, User};
use App\Services\PetugasMonitoringService;
use Illuminate\Support\Facades\DB;

class AssignmentService
{
    public function __construct(
        private NotifikasiService $notifikasiService,
        private PetugasMonitoringService $petugasMonitoringService,
    ) {}

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

            // 3. Log perubahan status (sertakan catatan assignment jika ada)
            $catatanLog = filled($data['instruksi'] ?? null)
                ? 'Ditugaskan ke petugas. Instruksi: ' . $data['instruksi']
                : 'Ditugaskan ke petugas.';
            $this->catatStatusLog($pengaduan, $supervisor, $statusLama, 'ditugaskan', $catatanLog);

            // 4. Petugas masuk status On-Duty (sibuk)
            $petugas = Petugas::with('user')->find($data['petugas_id']);
            if ($petugas && $petugas->status_tersedia !== 'tidak_aktif') {
                $petugas->update(['status_tersedia' => 'sibuk']);
            }

            // 5. Notifikasi ke petugas (termasuk ringkasan instruksi jika ada)
            if ($petugas && $petugas->user) {
                $pesanPetugas = "Anda mendapat tugas baru: pengaduan #{$pengaduan->nomor_tiket} di {$pengaduan->zona->nama_zona}.";
                if (filled($data['instruksi'] ?? null)) {
                    $ringkas = \Illuminate\Support\Str::limit($data['instruksi'], 120);
                    $pesanPetugas .= " Instruksi perbaikan: {$ringkas}";
                }
                $this->notifikasiService->kirim(
                    $petugas->user->id,
                    $pengaduan->id,
                    'Tugas Baru Ditugaskan',
                    $pesanPetugas,
                    'assignment'
                );
            }

            // 6. Notifikasi ke pelapor
            $this->notifikasiService->kirim(
                $pengaduan->pelapor->id,
                $pengaduan->id,
                'Petugas Sedang Dalam Perjalanan',
                "Pengaduan #{$pengaduan->nomor_tiket} telah ditugaskan ke petugas. Jadwal penanganan: "
                    . \Carbon\Carbon::parse($data['jadwal_penanganan'])->translatedFormat('d F Y, H:i') . ' WIB.',
                'status_berubah'
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

            if ($data['status_assignment'] === 'selesai' && $assignment->petugas) {
                $this->petugasMonitoringService->releaseIfNoActiveAssignments($assignment->petugas);
            }

            return $assignment->fresh();
        });
    }

    /**
     * Catat perubahan status ke tabel status_log via Eloquent.
     */
    private function catatStatusLog(Pengaduan $pengaduan, User $user, ?string $statusLama, string $statusBaru, ?string $catatan = null): void
    {
        StatusLog::create([
            'pengaduan_id' => $pengaduan->id,
            'user_id'      => $user->id,
            'status_lama'  => $statusLama,
            'status_baru'  => $statusBaru,
            'catatan'      => $catatan,
        ]);
    }
}

