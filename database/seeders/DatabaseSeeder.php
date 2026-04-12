<?php
// TANGGUNG JAWAB: Arthur Budi Maharesi — Master seeder
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,      // FARISHA  — 4 akun default
            ZonaSeeder::class,      // ARTHUR   — zona wilayah
            KategoriSeeder::class,  // ARTHUR   — kategori + SLA
            PelangganSeeder::class, // ARTHUR   — data pelanggan dummy
            PetugasSeeder::class,   // FARISHA  — data petugas dummy
            PengaduanSeeder::class, // SANITRA  — pengaduan dummy untuk testing
        ]);
    }
}
