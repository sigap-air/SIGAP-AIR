<?php

namespace Tests\Feature\Petugas;

use App\Models\Petugas;
use App\Models\User;
use App\Models\ZonaWilayah;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfilPetugasTest extends TestCase
{
    use RefreshDatabase;

    public function test_petugas_can_view_their_profile_page(): void
    {
        $zona = ZonaWilayah::create([
            'nama_zona' => 'Zona Timur',
            'kode_zona' => 'ZT-01',
            'deskripsi' => 'Wilayah layanan zona timur',
            'is_active' => true,
        ]);

        $user = User::create([
            'name' => 'Petugas Uji',
            'username' => 'petugas_uji',
            'email' => 'petugas-uji@example.com',
            'password' => Hash::make('password'),
            'role' => 'petugas',
            'no_telepon' => '08123456789',
            'is_active' => true,
        ]);

        Petugas::create([
            'user_id' => $user->id,
            'zona_id' => $zona->id,
            'nip' => 'PTG-0001',
            'status_tersedia' => 'tersedia',
        ]);

        $this->actingAs($user)
            ->get('/petugas/profil')
            ->assertOk()
            ->assertSeeText('Profil Saya')
            ->assertSeeText('Petugas Uji')
            ->assertSeeText('Status Petugas')
            ->assertSeeText('Tersedia')
            ->assertSeeText('Zona Timur');
    }

    public function test_non_petugas_cannot_access_petugas_profile_page(): void
    {
        $user = User::factory()->create([
            'role' => 'masyarakat',
        ]);

        $this->actingAs($user)
            ->get('/petugas/profil')
            ->assertForbidden();
    }

    public function test_petugas_without_profile_record_is_forbidden(): void
    {
        $user = User::factory()->create([
            'role' => 'petugas',
        ]);

        $this->actingAs($user)
            ->get('/petugas/profil')
            ->assertForbidden();
    }
}
