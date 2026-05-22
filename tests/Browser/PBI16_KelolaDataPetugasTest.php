<?php

/**
 * PBI-16 — Browser Test: Kelola Data Petugas Teknis
 *
 * TANGGUNG JAWAB: Farisha Huwaida Shofha
 *
 * Test Cases:
 *   TC-PET-001 : Admin dapat mengakses halaman daftar petugas
 *   TC-PET-002 : Admin dapat menambah petugas baru
 *   TC-PET-003 : Admin dapat melihat detail petugas
 *   TC-PET-004 : Admin dapat mengedit data petugas
 *   TC-PET-005 : Admin dapat mengubah status ketersediaan petugas
 *   TC-PET-006 : Admin dapat memfilter petugas berdasarkan status
 *   TC-PET-007 : Admin dapat menonaktifkan petugas
 *   TC-PET-008 : Validasi form menolak data tidak lengkap
 *   TC-PET-009 : Non-admin tidak dapat mengakses halaman petugas
 *   TC-PET-010 : Admin dapat mencari petugas berdasarkan nama
 */

namespace Tests\Browser;

use App\Models\Petugas;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PBI16_KelolaDataPetugasTest extends DuskTestCase
{
    // =========================================================
    // HELPER METHODS
    // =========================================================

    /**
     * Buat user dengan role tertentu.
     */
    private function buatUser(string $name, string $role): User
    {
        $unique = uniqid();
        $id = DB::table('users')->insertGetId([
            'name'              => $name,
            'username'          => 'user_' . $role . '_' . $unique,
            'email'             => $role . '_' . $unique . '@pbi16test.com',
            'password'          => Hash::make('password'),
            'role'              => $role,
            'is_active'         => true,
            'email_verified_at' => now(),
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);
        return User::find($id);
    }

    /**
     * Buat zona wilayah untuk keperluan test.
     */
    private function buatZona(string $nama = 'Zona Test PBI16'): int
    {
        return DB::table('zona_wilayah')->insertGetId([
            'nama_zona'  => $nama,
            'kode_zona'  => 'ZT' . uniqid(),
            'deskripsi'  => 'Zona untuk testing PBI 16',
            'is_active'  => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Buat petugas lengkap (user + record petugas).
     */
    private function buatPetugas(string $nama = 'Petugas Test', ?int $zonaId = null): Petugas
    {
        $user   = $this->buatUser($nama, 'petugas');
        $unique = uniqid();

        return Petugas::create([
            'user_id'         => $user->id,
            'zona_id'         => $zonaId,
            'nip'             => 'NIP-PBI16-' . $unique,
            'status_tersedia' => 'tersedia',
        ]);
    }

    // =========================================================
    // TEST CASES
    // =========================================================

    /**
     * TC-PET-001 : Admin dapat mengakses halaman daftar petugas
     *
     * @test
     */
    public function tc_pet_001_admin_dapat_mengakses_halaman_daftar_petugas()
    {
        $admin = $this->buatUser('Admin PBI16 001', 'admin');

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/petugas')
                ->assertPathIs('/admin/petugas')
                ->assertSee('Petugas Teknis')
                ->assertSee('Tambah Petugas')
                ->assertSee('Total Petugas');
        });
    }

    /**
     * TC-PET-002 : Admin dapat menambah petugas baru berhasil
     *
     * @test
     */
    public function tc_pet_002_admin_dapat_menambah_petugas_baru()
    {
        $admin  = $this->buatUser('Admin PBI16 002', 'admin');
        $zonaId = $this->buatZona('Zona Tambah PBI16');
        $unique = uniqid();

        $this->browse(function (Browser $browser) use ($admin, $zonaId, $unique) {
            $browser->loginAs($admin)
                ->visit('/admin/petugas/create')
                ->assertSee('Tambah Petugas Teknis')
                ->type('#name', 'Rudi Teknisi PBI16')
                ->type('#email', 'rudi_' . $unique . '@pdam.test')
                ->type('#username', 'rudi_' . $unique)
                ->type('#no_telepon', '081234567890')
                ->type('#nip', 'NIP-' . $unique)
                ->type('#password', 'password123')
                ->type('#password_confirmation', 'password123')
                ->select('#status_tersedia', 'tersedia')
                ->select('#zona_id', $zonaId)
                ->click('#btn-simpan-petugas')
                ->assertPathIs('/admin/petugas')
                ->assertSee('Petugas berhasil ditambahkan')
                ->assertSee('Rudi Teknisi PBI16');
        });
    }

    /**
     * TC-PET-003 : Admin dapat melihat detail petugas
     *
     * @test
     */
    public function tc_pet_003_admin_dapat_melihat_detail_petugas()
    {
        $admin   = $this->buatUser('Admin PBI16 003', 'admin');
        $zonaId  = $this->buatZona('Zona Detail PBI16');
        $petugas = $this->buatPetugas('Petugas Detail PBI16', $zonaId);

        $this->browse(function (Browser $browser) use ($admin, $petugas) {
            $browser->loginAs($admin)
                ->visit('/admin/petugas/' . $petugas->id)
                ->assertPathIs('/admin/petugas/' . $petugas->id)
                ->assertSee('Petugas Detail PBI16')
                ->assertSee('Informasi Lengkap')
                ->assertSee('Riwayat Tugas')
                ->assertSee('Statistik Tugas')
                ->assertSee('Edit Data');
        });
    }

    /**
     * TC-PET-004 : Admin dapat mengedit data petugas
     *
     * @test
     */
    public function tc_pet_004_admin_dapat_mengedit_data_petugas()
    {
        $admin   = $this->buatUser('Admin PBI16 004', 'admin');
        $zonaId  = $this->buatZona('Zona Edit PBI16');
        $petugas = $this->buatPetugas('Petugas Edit PBI16', $zonaId);

        $this->browse(function (Browser $browser) use ($admin, $petugas) {
            $browser->loginAs($admin)
                ->visit('/admin/petugas/' . $petugas->id . '/edit')
                ->assertSee('Edit Data Petugas')
                ->assertInputValue('#name', 'Petugas Edit PBI16')
                ->clear('#name')
                ->type('#name', 'Petugas Edit PBI16 Updated')
                ->click('#btn-update-petugas')
                ->assertPathIs('/admin/petugas')
                ->assertSee('Data petugas berhasil diperbarui')
                ->assertSee('Petugas Edit PBI16 Updated');
        });
    }

    /**
     * TC-PET-005 : Admin dapat mengubah status ketersediaan petugas
     *
     * @test
     */
    public function tc_pet_005_admin_dapat_mengubah_status_ketersediaan()
    {
        $admin   = $this->buatUser('Admin PBI16 005', 'admin');
        $petugas = $this->buatPetugas('Petugas Status PBI16');

        $this->browse(function (Browser $browser) use ($admin, $petugas) {
            $browser->loginAs($admin)
                ->visit('/admin/petugas/' . $petugas->id . '/edit')
                ->assertSee('Edit Data Petugas')
                ->select('#status_tersedia', 'sibuk')
                ->click('#btn-update-petugas')
                ->assertPathIs('/admin/petugas')
                ->assertSee('Data petugas berhasil diperbarui');
        });

        // Verifikasi perubahan di database
        $this->assertDatabaseHas('petugas', [
            'id'              => $petugas->id,
            'status_tersedia' => 'sibuk',
        ]);
    }

    /**
     * TC-PET-006 : Admin dapat memfilter petugas berdasarkan status
     *
     * @test
     */
    public function tc_pet_006_admin_dapat_memfilter_petugas_berdasarkan_status()
    {
        $admin           = $this->buatUser('Admin PBI16 006', 'admin');
        $petugasTersedia = $this->buatPetugas('Petugas Tersedia PBI16');
        $petugasSibuk    = $this->buatPetugas('Petugas Sibuk PBI16');

        // Set status sibuk
        $petugasSibuk->update(['status_tersedia' => 'sibuk']);

        $this->browse(function (Browser $browser) use ($admin, $petugasTersedia, $petugasSibuk) {
            $browser->loginAs($admin)
                ->visit('/admin/petugas')
                ->select('#filter-status', 'tersedia')
                ->click('#btn-filter-petugas')
                ->assertPathIs('/admin/petugas')
                ->assertSee('Petugas Tersedia PBI16')
                ->assertDontSee('Petugas Sibuk PBI16');
        });
    }

    /**
     * TC-PET-007 : Admin dapat menonaktifkan petugas tanpa tugas aktif
     *
     * @test
     */
    public function tc_pet_007_admin_dapat_menonaktifkan_petugas()
    {
        $admin   = $this->buatUser('Admin PBI16 007', 'admin');
        $petugas = $this->buatPetugas('Petugas Nonaktif PBI16');

        $this->browse(function (Browser $browser) use ($admin, $petugas) {
            $browser->loginAs($admin)
                ->visit('/admin/petugas/' . $petugas->id . '/edit')
                ->assertSee('Nonaktifkan')
                ->click('#btn-nonaktifkan-dari-edit')
                ->acceptDialog()
                ->assertPathIs('/admin/petugas')
                ->assertSee('Petugas berhasil dinonaktifkan');
        });

        // Verifikasi status di database
        $this->assertDatabaseHas('petugas', [
            'id'              => $petugas->id,
            'status_tersedia' => 'tidak_aktif',
        ]);
    }

    /**
     * TC-PET-008 : Validasi form menolak data tidak lengkap
     *
     * @test
     */
    public function tc_pet_008_validasi_form_menolak_data_tidak_lengkap()
    {
        $admin = $this->buatUser('Admin PBI16 008', 'admin');

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/petugas/create')
                ->assertSee('Tambah Petugas Teknis')
                // Submit tanpa mengisi apapun
                ->click('#btn-simpan-petugas')
                // Tetap di halaman create
                ->assertPathIs('/admin/petugas/create');
        });
    }

    /**
     * TC-PET-009 : Non-admin tidak dapat mengakses halaman petugas
     *
     * @test
     */
    public function tc_pet_009_non_admin_tidak_dapat_akses_halaman_petugas()
    {
        $masyarakat = $this->buatUser('Masyarakat PBI16 009', 'masyarakat');

        $this->browse(function (Browser $browser) use ($masyarakat) {
            $browser->loginAs($masyarakat)
                ->visit('/admin/petugas')
                ->assertDontSee('Petugas Teknis')
                ->assertDontSee('Tambah Petugas');
        });
    }

    /**
     * TC-PET-010 : Admin dapat mencari petugas berdasarkan nama
     *
     * @test
     */
    public function tc_pet_010_admin_dapat_mencari_petugas_berdasarkan_nama()
    {
        $admin   = $this->buatUser('Admin PBI16 010', 'admin');
        $unique  = uniqid();
        $petugas = $this->buatPetugas('Petugas Cari ' . $unique);

        $this->browse(function (Browser $browser) use ($admin, $unique) {
            $browser->loginAs($admin)
                ->visit('/admin/petugas')
                ->type('#input-search-petugas', 'Petugas Cari ' . $unique)
                ->click('#btn-filter-petugas')
                ->assertPathIs('/admin/petugas')
                ->assertSee('Petugas Cari ' . $unique);
        });
    }
}
