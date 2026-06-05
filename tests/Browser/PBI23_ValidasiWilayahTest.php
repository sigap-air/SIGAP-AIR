<?php

namespace Tests\Browser;

use App\Models\KategoriPengaduan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PBI23_ValidasiWilayahTest extends DuskTestCase
{
    private function buatUser(string $name, string $role): User
    {
        $unique = uniqid();
        $id = DB::table('users')->insertGetId([
            'name'              => $name,
            'username'          => 'user_' . $role . '_' . $unique,
            'email'             => $role . '_' . $unique . '@pbi23test.com',
            'password'          => Hash::make('password'),
            'role'              => $role,
            'is_active'         => true,
            'email_verified_at' => now(),
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);
        return User::find($id);
    }

    private function buatZona(string $nama = 'Zona Test PBI23'): int
    {
        return DB::table('zona_wilayah')->insertGetId([
            'nama_zona'  => $nama,
            'kode_zona'  => 'ZT' . uniqid(),
            'deskripsi'  => 'Zona untuk testing PBI 23',
            'is_active'  => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function buatKategori(): int
    {
        return DB::table('kategori_pengaduan')->insertGetId([
            'nama_kategori' => 'Kategori PBI23',
            'deskripsi' => 'Testing',
            'sla_jam' => 24,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * TC-ZON-001 : Validasi wilayah otomatis saat mengisi lokasi yang sesuai
     * @test
     */
    public function validasi_wilayah_otomatis_sesuai()
    {
        $masyarakat = $this->buatUser('Warga PBI23', 'masyarakat');
        $zonaId = $this->buatZona('Zona Utara');
        $kategoriId = $this->buatKategori();

        $this->browse(function (Browser $browser) use ($masyarakat, $zonaId, $kategoriId) {
            $browser->loginAs($masyarakat)
                ->visit('/masyarakat/pengaduan/create')
                ->select('kategori_id', $kategoriId)
                ->select('zona_id', $zonaId)
                ->type('lokasi', 'Jalan Raya Utara No 123') // Mengandung kata 'Utara'
                ->pause(1500) // Tunggu debounce
                ->assertSee('Lokasi sesuai dengan zona.')
                ->type('no_telepon', '081234567890')
                ->type('deskripsi', 'Air mati sejak pagi hari ini.')
                ->attach('foto_bukti', __DIR__.'/files/test-image.jpg')
                ->click('button[type="submit"]')
                ->waitForLocation('/masyarakat/pengaduan')
                ->assertSee('tiket-sukses');
        });
        
        $this->assertDatabaseHas('pengaduan', [
            'user_id' => $masyarakat->id,
            'is_zona_valid' => 1,
        ]);
    }

    /**
     * TC-ZON-002 : Peringatan validasi wilayah saat lokasi tidak sesuai
     * @test
     */
    public function peringatan_validasi_wilayah_saat_lokasi_tidak_sesuai()
    {
        $masyarakat = $this->buatUser('Warga PBI23 B', 'masyarakat');
        $zonaId = $this->buatZona('Zona Selatan');
        $kategoriId = $this->buatKategori();

        $this->browse(function (Browser $browser) use ($masyarakat, $zonaId, $kategoriId) {
            $browser->loginAs($masyarakat)
                ->visit('/masyarakat/pengaduan/create')
                ->select('kategori_id', $kategoriId)
                ->select('zona_id', $zonaId)
                ->type('lokasi', 'Jalan Raya Utara No 123') // Mengandung kata 'Utara', tidak cocok dengan 'Selatan'
                ->pause(1500) // Tunggu debounce
                ->assertSee('Peringatan: Lokasi Anda tampaknya berada di luar zona yang dipilih.')
                ->type('no_telepon', '081234567890')
                ->type('deskripsi', 'Air mati sejak pagi hari ini.')
                ->attach('foto_bukti', __DIR__.'/files/test-image.jpg')
                ->click('button[type="submit"]')
                ->waitForLocation('/masyarakat/pengaduan')
                ->assertSee('tiket-sukses');
        });
        
        $this->assertDatabaseHas('pengaduan', [
            'user_id' => $masyarakat->id,
            'is_zona_valid' => 0,
        ]);
    }
}
