<?php

/**
 * PBI-07 — Browser Test: Detail Tugas Petugas (Peta Lokasi)
 *
 * TANGGUNG JAWAB: Arthur Budi Maharesi
 *
 * Test Cases (Fokus Pemetaan):
 *   TC-PBI07-001 : Petugas dapat melihat peta lokasi dan link Google Maps di halaman detail tugas
 */

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PBI07_DetailTugasPetaTest extends DuskTestCase
{
    private function buatUser(string $name, string $role): User
    {
        $unique = uniqid();
        $id = DB::table('users')->insertGetId([
            'name'              => $name,
            'username'          => 'user_' . $role . '_' . $unique,
            'email'             => $role . '_' . $unique . '@petugas-peta.com',
            'password'          => Hash::make('password'),
            'role'              => $role,
            'is_active'         => true,
            'email_verified_at' => now(),
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);
        return User::find($id);
    }

    private function buatTugasTest(User $petugasUser): int
    {
        // 1. Buat Kategori
        $kategoriId = DB::table('kategori_pengaduan')->insertGetId([
            'nama_kategori' => 'Kategori Peta Test',
            'kode_kategori' => 'KPT' . uniqid(),
            'sla_jam'       => 24,
            'is_active'     => true,
        ]);

        // 2. Buat Zona
        $zonaId = DB::table('zona_wilayah')->insertGetId([
            'nama_zona' => 'Zona Test',
            'kode_zona' => 'ZT-PT-' . uniqid(),
            'is_active' => true,
        ]);

        // 3. Buat Pelapor
        $pelaporId = $this->buatUser('Pelapor Test', 'masyarakat')->id;

        // 4. Buat Pengaduan
        $pengaduanId = DB::table('pengaduan')->insertGetId([
            'nomor_tiket' => 'TKT-PT-' . time(),
            'user_id' => $pelaporId,
            'kategori_id' => $kategoriId,
            'zona_id' => $zonaId,
            'lokasi' => 'Jl. Test Peta No 123',
            'latitude' => '-6.9175',
            'longitude' => '107.6191',
            'deskripsi' => 'Testing map visibility untuk petugas',
            'status' => 'sedang_diproses',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 5. Buat Petugas Record
        $petugasId = DB::table('petugas')->insertGetId([
            'user_id' => $petugasUser->id,
            'nip' => 'NIP' . time(),
            'status_tersedia' => 'sibuk',
        ]);

        // 6. Buat Assignment Tugas
        $assignmentId = DB::table('assignment')->insertGetId([
            'pengaduan_id' => $pengaduanId,
            'petugas_id' => $petugasId,
            'supervisor_id' => $petugasUser->id,
            'status_assignment' => 'diproses',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $assignmentId;
    }

    private function loginAsPetugas(Browser $browser, User $petugas): Browser
    {
        return $browser->loginAs($petugas);
    }

    public function test_tc_pbi07_001_peta_lokasi_tampil_di_detail_tugas()
    {
        $petugasUser = $this->buatUser('Petugas Map Test', 'petugas');
        $assignmentId = $this->buatTugasTest($petugasUser);
        $tugas = \Illuminate\Support\Facades\DB::table('assignment')
                    ->join('pengaduan', 'assignment.pengaduan_id', '=', 'pengaduan.id')
                    ->where('assignment.id', $assignmentId)
                    ->select('pengaduan.nomor_tiket')
                    ->first();

        $this->browse(function (Browser $browser) use ($petugasUser, $assignmentId, $tugas) {
            $this->loginAsPetugas($browser, $petugasUser)
                ->visit('/petugas/tugas/' . $assignmentId)
                ->assertSee($tugas->nomor_tiket)
                ->assertPresent('#map') // Memastikan div map dirender
                ->assertSee('Buka Google Maps') // Memastikan link GMap ada
                ->pause(1500) // Tunggu Leaflet render
                ->screenshot('TC_PBI07_001_peta_lokasi_tampil');
        });
    }
}
