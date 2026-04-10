<?php
// TANGGUNG JAWAB: Sanitra Savitri (PBI-04) — Data dummy untuk testing Dusk
namespace Database\Seeders;

use App\Models\{User, Pengaduan, Kategori, Zona};
use Illuminate\Database\Seeder;

class PengaduanSeeder extends Seeder
{
    public function run(): void
    {
        $user     = User::where('role', 'masyarakat')->first();
        $kategori = Kategori::first();
        $zona     = Zona::first();

        if ($user && $kategori && $zona) {
            Pengaduan::create([
                'nomor_tiket'       => 'SIGAP-20240101-0001',
                'user_id'           => $user->id,
                'kategori_id'       => $kategori->id,
                'zona_id'           => $zona->id,
                'lokasi'            => 'Jl. Contoh No. 1',
                'deskripsi'         => 'Air keluar sangat keruh sejak pagi hari.',
                'status'            => 'menunggu_verifikasi',
                'tanggal_pengajuan' => now(),
            ]);
        }
    }
}
