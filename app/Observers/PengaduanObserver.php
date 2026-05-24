<?php

namespace App\Observers;

use App\Models\Pengaduan;
use App\Models\Notifikasi;

class PengaduanObserver
{
    /**
     * Handle the Pengaduan "updated" event.
     */
    public function updated(Pengaduan $pengaduan): void
    {
        // Periksa apakah field 'status' berubah
        if ($pengaduan->isDirty('status')) {
            $oldStatus = $pengaduan->getOriginal('status');
            $newStatus = $pengaduan->status;

            // Format status agar lebih mudah dibaca (misal: "menunggu_verifikasi" -> "Menunggu Verifikasi")
            $formattedOld = ucwords(str_replace('_', ' ', $oldStatus));
            $formattedNew = ucwords(str_replace('_', ' ', $newStatus));

            // Buat notifikasi untuk user/pelapor
            Notifikasi::create([
                'user_id' => $pengaduan->user_id,
                'pengaduan_id' => $pengaduan->id,
                'judul' => 'Status Pengaduan Berubah',
                'pesan' => "Pengaduan Anda (#{$pengaduan->nomor_tiket}) telah berubah status dari '{$formattedOld}' menjadi '{$formattedNew}'.",
                'tipe' => 'status_berubah',
                'is_read' => false,
            ]);
        }
    }
}
