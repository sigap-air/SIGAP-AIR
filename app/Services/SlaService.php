<?php
/**
 * SlaService — Hitung dan monitoring SLA pengaduan
 * TANGGUNG JAWAB: Falah Adhi Chandra (PBI 9)
 */
namespace App\Services;

use App\Models\{Pengaduan, Sla};

class SlaService
{
    /**
     * Cek apakah pengaduan sudah overdue, lalu update flag-nya
     */
    public function cekDanUpdate(Pengaduan $pengaduan): void
    {
        $sla = $pengaduan->sla;
        if (!$sla || $sla->is_fulfilled) return;

        if (now()->greaterThan($sla->batas_waktu) && !$sla->is_overdue) {
            $sla->update(['status_sla' => 'overdue', 'is_flagged' => true]);

            // Kirim alert ke semua supervisor
            $supervisors = \App\Models\User::where('role', 'supervisor')->where('is_active', true)->get();
            foreach ($supervisors as $supervisor) {
                \App\Models\Notifikasi::create([
                    'user_id'      => $supervisor->id,
                    'pengaduan_id' => $pengaduan->id,
                    'judul'        => '⚠️ SLA Terlampaui',
                    'pesan'        => "Pengaduan #{$pengaduan->nomor_tiket} di {$pengaduan->zona->nama_zona} telah melewati batas SLA. Segera tindak lanjuti!",
                    'is_read'      => false,
                ]);
            }
        }
    }

    /**
     * Tandai SLA terpenuhi saat pengaduan selesai
     */
    public function tandaiTerpenuhi(Pengaduan $pengaduan): void
    {
        $pengaduan->sla?->update([
            'status_sla'  => 'terpenuhi',
            'resolved_at' => now(),
        ]);
    }
}
