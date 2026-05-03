<?php

namespace Tests\Browser;

use App\Models\KategoriPengaduan;
use App\Models\Pengaduan;
use App\Models\Petugas;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PBI18_LaporanKinerjaPetugasTest extends DuskTestCase
{
    private function buatUser($name, $username, $email, $role)
    {
        $unique = uniqid();

        $id = DB::table('users')->insertGetId([
            'name' => $name,
            'username' => $username . '_' . $unique,
            'email' => $email . '_' . $unique . '@test.com',
            'password' => Hash::make('password'),
            'role' => $role,
            'no_telepon' => '08123456789',
            'is_active' => 1,
            'email_verified_at' => now(),
            'remember_token' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return User::find($id);
    }

    private function buatZona($nama = 'Zona Utara PBI18')
    {
        return DB::table('zona_wilayah')->insertGetId([
            'nama_zona' => $nama,
            'kode_zona' => 'ZU' . uniqid(),
            'deskripsi' => 'Zona untuk testing PBI 18',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function buatDataKinerja()
    {
        $supervisor = $this->buatUser(
            'Dewi Supervisor',
            'supervisor_pbi18',
            'supervisor_pbi18',
            'supervisor'
        );

        $masyarakat = $this->buatUser(
            'Budi Masyarakat',
            'masyarakat_pbi18',
            'masyarakat_pbi18',
            'masyarakat'
        );

        $userPetugas = $this->buatUser(
            'Roni Petugas',
            'petugas_pbi18',
            'petugas_pbi18',
            'petugas'
        );

        $zonaId = $this->buatZona('Zona Utara PBI18');

        $kategori = KategoriPengaduan::create([
            'nama_kategori' => 'Kebocoran Pipa PBI18 ' . uniqid(),
            'kode_kategori' => 'KB' . uniqid(),
            'deskripsi' => 'Kategori testing PBI 18',
            'sla_jam' => 24,
            'is_active' => true,
        ]);

        $petugas = Petugas::create([
            'user_id' => $userPetugas->id,
            'zona_id' => $zonaId,
            'nip' => 'PTG-PBI18-' . uniqid(),
            'status_tersedia' => 'tersedia',
        ]);

        $pengaduan = Pengaduan::create([
            'nomor_tiket' => 'TKT-PBI18-' . uniqid(),
            'user_id' => $masyarakat->id,
            'kategori_id' => $kategori->id,
            'zona_id' => $zonaId,
            'lokasi' => 'Jl. Testing PBI 18',
            'deskripsi' => 'Air tidak mengalir',
            'status' => 'selesai',
            'tanggal_pengajuan' => now()->subDays(2),
        ]);

        DB::table('assignment')->insert([
            'pengaduan_id' => $pengaduan->id,
            'petugas_id' => $petugas->id,
            'supervisor_id' => $supervisor->id,
            'jadwal_penanganan' => now()->subDay(),
            'status_assignment' => 'selesai',
            'tanggal_selesai' => now(),
            'created_at' => now()->subDays(2),
            'updated_at' => now(),
        ]);

        Rating::create([
            'pengaduan_id' => $pengaduan->id,
            'user_id' => $masyarakat->id,
            'rating' => 5,
            'komentar' => 'Pelayanan sangat baik',
        ]);

        return [$supervisor, $masyarakat, $userPetugas, $zonaId];
    }

    /** @test */
    public function tc_kinerja_001_menguji_akses_halaman_laporan_kinerja_petugas()
    {
        [$supervisor] = $this->buatDataKinerja();

        $this->browse(function (Browser $browser) use ($supervisor) {
            $browser->loginAs($supervisor)
                ->visit('/supervisor/kinerja')
                ->assertPathIs('/supervisor/kinerja')
                ->assertSee('Laporan Kinerja Petugas')
                ->assertSee('Export CSV')
                ->assertSee('Filter')
                ->assertSee('Reset')
                ->assertSee('Nama Petugas')
                ->assertSee('No. Pegawai')
                ->assertSee('Total Tugas')
                ->assertSee('Selesai')
                ->assertSee('Roni Petugas');
        });
    }

    /** @test */
    public function tc_kinerja_002_menguji_filter_laporan_berdasarkan_zona_dan_rentang_tanggal()
    {
        [$supervisor, , , $zonaId] = $this->buatDataKinerja();

        $this->browse(function (Browser $browser) use ($supervisor, $zonaId) {
            $browser->loginAs($supervisor)
                ->visit('/supervisor/kinerja')
                ->select('zona_id', $zonaId)
                ->type('dari', now()->subDays(7)->format('Y-m-d'))
                ->type('sampai', now()->format('Y-m-d'))
                ->press('Filter')
                ->assertPathIs('/supervisor/kinerja')
                ->assertQueryStringHas('zona_id', (string) $zonaId)
                ->assertSee('Roni Petugas')
                ->assertSee('Nama Petugas')
                ->assertSee('No. Pegawai')
                ->assertSee('Total Tugas')
                ->assertSee('Selesai');
        });
    }

    /** @test */
    public function tc_kinerja_003_menguji_reset_filter_laporan_kinerja()
    {
        [$supervisor, , , $zonaId] = $this->buatDataKinerja();

        $this->browse(function (Browser $browser) use ($supervisor, $zonaId) {
            $browser->loginAs($supervisor)
                ->visit('/supervisor/kinerja?zona_id=' . $zonaId . '&dari=' . now()->subDays(7)->format('Y-m-d') . '&sampai=' . now()->format('Y-m-d'))
                ->assertSee('Laporan Kinerja Petugas')
                ->assertSee('Roni Petugas')
                ->clickLink('Reset')
                ->assertPathIs('/supervisor/kinerja')
                ->assertSee('Laporan Kinerja Petugas')
                ->assertSee('Nama Petugas')
                ->assertSee('Roni Petugas');
        });
    }

    /** @test */
    public function tc_kinerja_004_menguji_kondisi_laporan_ketika_data_tidak_ditemukan()
    {
        [$supervisor] = $this->buatDataKinerja();

        $zonaKosongId = $this->buatZona('Zona Kosong PBI18');

        $url = '/supervisor/kinerja?zona_id=' . $zonaKosongId
            . '&dari=' . now()->addYear()->format('Y-m-d')
            . '&sampai=' . now()->addYear()->addDays(7)->format('Y-m-d');

        $this->browse(function (Browser $browser) use ($supervisor, $url) {
            $browser->loginAs($supervisor)
                ->visit($url)
                ->assertPathIs('/supervisor/kinerja')
                ->assertSee('Laporan Kinerja Petugas')
                ->assertSee('Nama Petugas')
                ->assertDontSee('Roni Petugas');
        });
    }

    /** @test */
    public function tc_kinerja_005_menguji_export_laporan_kinerja_ke_csv()
    {
        [$supervisor] = $this->buatDataKinerja();

        $response = $this->actingAs($supervisor)
            ->get('/supervisor/kinerja/export-excel');

        $response->assertOk();

        $this->assertStringContainsString(
            'attachment',
            strtolower($response->headers->get('content-disposition'))
        );
    }

    /** @test */
    public function tc_kinerja_006_menguji_export_laporan_setelah_filter_diterapkan()
    {
        [$supervisor, , , $zonaId] = $this->buatDataKinerja();

        $response = $this->actingAs($supervisor)
            ->get('/supervisor/kinerja/export-excel?zona_id=' . $zonaId
                . '&dari=' . now()->subDays(7)->format('Y-m-d')
                . '&sampai=' . now()->format('Y-m-d'));

        $response->assertOk();

        $this->assertStringContainsString(
            'attachment',
            strtolower($response->headers->get('content-disposition'))
        );
    }
    /** @test */
    public function tc_kinerja_007_menguji_keamanan_akses_halaman_laporan_kinerja()
    {
        $masyarakat = $this->buatUser(
            'Masyarakat Tidak Boleh Akses',
            'masyarakat_noakses_pbi18',
            'masyarakat_noakses_pbi18',
            'masyarakat'
        );

        $this->browse(function (Browser $browser) use ($masyarakat) {
            $browser->loginAs($masyarakat)
                ->visit('/supervisor/kinerja')
                ->assertDontSee('Laporan Kinerja Petugas')
                ->assertDontSee('Export CSV')
                ->assertDontSee('Nama Petugas');
        });
    }
}