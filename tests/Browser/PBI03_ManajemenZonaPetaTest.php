<?php

/**
 * PBI-03 — Browser Test: Manajemen Zona Peta (Leaflet)
 *
 * TANGGUNG JAWAB: Arthur Budi Maharesi
 *
 * Test Cases (Fokus Pemetaan):
 *   TC-PBI03-001 : Admin dapat melihat form tambah zona beserta peta
 *   TC-PBI03-002 : Admin dapat menyimpan zona wilayah dengan geo_boundary
 *   TC-PBI03-003 : Backend Assertion memverifikasi data polygon tersimpan
 */

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PBI03_ManajemenZonaPetaTest extends DuskTestCase
{
    // =========================================================
    // HELPER METHODS
    // =========================================================

    private function buatUser(string $name, string $role): User
    {
        $unique = uniqid();
        $id = DB::table('users')->insertGetId([
            'name'              => $name,
            'username'          => 'user_' . $role . '_' . $unique,
            'email'             => $role . '_' . $unique . '@pbi03test.com',
            'password'          => Hash::make('password'),
            'role'              => $role,
            'is_active'         => true,
            'email_verified_at' => now(),
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);
        return User::find($id);
    }

    private function loginAsAdmin(Browser $browser): Browser
    {
        $admin = $this->buatUser('Admin Test', 'admin');
        return $browser->loginAs($admin);
    }

    // =========================================================
    // TEST CASES
    // =========================================================

    public function test_tc_pbi03_001_peta_tampil_saat_buat_zona()
    {
        $this->browse(function (Browser $browser) {
            $this->loginAsAdmin($browser)
                ->visit('/admin/zona/create')
                ->assertSee('Batas Wilayah Zona (Polygon)')
                ->assertPresent('#map')
                ->assertPresent('#geo_boundary')
                ->pause(1500) // Tunggu peta selesai dirender
                ->screenshot('TC_PBI03_001_peta_tampil');
        });
    }

    public function test_tc_pbi03_002_dan_003_admin_dapat_menyimpan_polygon_batas_wilayah()
    {
        $unique = uniqid();
        $kodeZona = 'ZTEST-' . $unique;
        
        // Data polygon mock GeoJSON
        $geoJson = '{"type":"Polygon","coordinates":[[[107.61,-6.91],[107.62,-6.91],[107.62,-6.92],[107.61,-6.92],[107.61,-6.91]]]}';

        $this->browse(function (Browser $browser) use ($kodeZona, $geoJson) {
            $this->loginAsAdmin($browser)
                ->visit('/admin/zona/create')
                ->type('nama_zona', 'Zona Testing Peta')
                ->type('kode_zona', $kodeZona)
                ->type('deskripsi', 'Zona ini diuji menggunakan Laravel Dusk untuk validasi geo_boundary.')
                ->pause(1000);
            
            // Injeksi GeoJSON ke hidden input (Mensimulasikan Leaflet Draw)
            $browser->script("document.getElementById('geo_boundary').value = '{$geoJson}';");
            
            $browser->press('Simpan Zona')
                ->waitForLocation('/admin/zona')
                ->assertSee('berhasil ditambahkan')
                ->screenshot('TC_PBI03_002_simpan_zona_peta_sukses');
        });

        // AC-003: Backend Assertion
        $this->assertDatabaseHas('zona_wilayah', [
            'kode_zona' => $kodeZona,
        ]);
        
        $zona = DB::table('zona_wilayah')->where('kode_zona', $kodeZona)->first();
        $this->assertNotNull($zona->geo_boundary, 'Geo Boundary tidak boleh null di database');
        $this->assertEquals(
            json_decode($geoJson, true),
            json_decode($zona->geo_boundary, true),
            'Data polygon tidak sesuai dengan yang di-submit'
        );
    }
}
