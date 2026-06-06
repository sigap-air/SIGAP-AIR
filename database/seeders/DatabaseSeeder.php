<?php

namespace Database\Seeders;

use App\Models\KategoriPengaduan;
use App\Models\Petugas;
use App\Models\User;
use App\Models\ZonaWilayah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seeder Zona Wilayah (Bandung — 4 wilayah utama)
        $zonaBdgUtara = ZonaWilayah::updateOrCreate(
            ['kode_zona' => 'BDG-U01'],
            [
                'nama_zona' => 'Bandung Utara',
                'deskripsi' => 'Wilayah pelayanan Bandung Utara meliputi Kec. Cidadap, Coblong, Bandung Wetan, Cibeunying Kaler, Cibeunying Kidul, Sukasari',
                'is_active' => true,
            ]
        )->id;

        $zonaBdgSelatan = ZonaWilayah::updateOrCreate(
            ['kode_zona' => 'BDG-S02'],
            [
                'nama_zona' => 'Bandung Selatan',
                'deskripsi' => 'Wilayah pelayanan Bandung Selatan meliputi Kec. Lengkong, Regol, Bandung Kidul, Buahbatu, Kiaracondong',
                'is_active' => true,
            ]
        )->id;

        $zonaBdgBarat = ZonaWilayah::updateOrCreate(
            ['kode_zona' => 'BDG-B03'],
            [
                'nama_zona' => 'Bandung Barat',
                'deskripsi' => 'Wilayah pelayanan Bandung Barat meliputi Kec. Andir, Cicendo, Sukajadi, Babakan Ciparay, Bojongloa Kaler, Bojongloa Kidul',
                'is_active' => true,
            ]
        )->id;

        $zonaBdgTimur = ZonaWilayah::updateOrCreate(
            ['kode_zona' => 'BDG-T04'],
            [
                'nama_zona' => 'Bandung Timur',
                'deskripsi' => 'Wilayah pelayanan Bandung Timur meliputi Kec. Arcamanik, Antapani, Mandalajati, Cibiru, Ujungberung',
                'is_active' => true,
            ]
        )->id;

        // 2. Seeder Kategori Pengaduan
        KategoriPengaduan::updateOrCreate(
            ['kode_kategori' => 'AK-01'],
            [
                'nama_kategori' => 'Air Keruh',
                'deskripsi' => 'Laporan terkait air berwarna keruh atau kotor',
                'sla_jam' => 24,
                'is_active' => true,
            ]
        );

        KategoriPengaduan::updateOrCreate(
            ['kode_kategori' => 'AM-02'],
            [
                'nama_kategori' => 'Air Macet',
                'deskripsi' => 'Laporan tidak ada aliran air sama sekali',
                'sla_jam' => 12,
                'is_active' => true,
            ]
        );

        KategoriPengaduan::updateOrCreate(
            ['kode_kategori' => 'AB-03'],
            [
                'nama_kategori' => 'Air Berbau',
                'deskripsi' => 'Laporan air berbau tidak sedap atau berbau kimia',
                'sla_jam' => 48,
                'is_active' => true,
            ]
        );

        // 3. Seeder Akun Users
        $password = Hash::make('password');

        $adminId = User::updateOrCreate(
            ['email' => 'admin@sigapair.test'],
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'password' => $password,
                'role' => 'admin',
                'is_active' => true,
            ]
        )->id;

        $supervisorId = User::updateOrCreate(
            ['email' => 'supervisor@sigapair.test'],
            [
                'name' => 'Supervisor',
                'username' => 'supervisor',
                'password' => $password,
                'role' => 'supervisor',
                'is_active' => true,
            ]
        )->id;

        $petugasId = User::updateOrCreate(
            ['email' => 'petugas@sigapair.test'],
            [
                'name' => 'Petugas Lapangan',
                'username' => 'petugas',
                'password' => $password,
                'role' => 'petugas',
                'is_active' => true,
            ]
        )->id;

        $masyarakatId = User::updateOrCreate(
            ['email' => 'masyarakat@sigapair.test'],
            [
                'name' => 'Masyarakat Umum',
                'username' => 'masyarakat',
                'password' => $password,
                'role' => 'masyarakat',
                'is_active' => true,
            ]
        )->id;

        // 4. Register Petugas -> Zona Pertama
        Petugas::updateOrCreate(
            ['user_id' => $petugasId],
            [
                'zona_id' => $zonaBdgUtara,
                'nip' => 'PEG-1001',
                'status_tersedia' => 'tersedia',
            ]
        );
    }
}
