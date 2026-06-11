<?php

/**
 * Browser Test: Supervisor Zona Peta
 *
 * TANGGUNG JAWAB: Arthur Budi Maharesi
 *
 * Test Cases (Fokus Pemetaan):
 *   TC-SPV-001 : Supervisor dapat melihat container peta wilayah di halaman detail zona
 */

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SupervisorZonaPetaTest extends DuskTestCase
{
    private function buatUser(string $name, string $role): User
    {
        $unique = uniqid();
        $id = DB::table('users')->insertGetId([
            'name'              => $name,
            'username'          => 'user_' . $role . '_' . $unique,
            'email'             => $role . '_' . $unique . '@supervisor-peta.com',
            'password'          => Hash::make('password'),
            'role'              => $role,
            'is_active'         => true,
            'email_verified_at' => now(),
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);
        return User::find($id);
    }

    private function buatZonaTest(): int
    {
        return DB::table('zona_wilayah')->insertGetId([
            'nama_zona'    => 'Zona Test SPV',
            'kode_zona'    => 'Z-SPV-' . uniqid(),
            'geo_boundary' => '{"type":"Polygon","coordinates":[[[107.61,-6.91],[107.62,-6.91],[107.62,-6.92],[107.61,-6.92],[107.61,-6.91]]]}',
            'is_active'    => true,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
    }

    private function loginAsSupervisor(Browser $browser): Browser
    {
        $spv = $this->buatUser('Supervisor Test', 'supervisor');
        return $browser->loginAs($spv);
    }

    public function test_tc_spv_001_peta_zona_tampil_di_detail_zona()
    {
        $zonaId = $this->buatZonaTest();

        $this->browse(function (Browser $browser) use ($zonaId) {
            $this->loginAsSupervisor($browser)
                ->visit('/supervisor/zona/' . $zonaId)
                ->assertSee('Detail Zona Wilayah')
                ->assertPresent('#map') // Memastikan div map dirender
                ->pause(1500) // Tunggu Leaflet render
                ->screenshot('TC_SPV_001_peta_zona_tampil');
        });
    }
}
