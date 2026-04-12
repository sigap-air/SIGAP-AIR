<?php
// TANGGUNG JAWAB: Farisha Huwaida Shofha (PBI-17)
namespace Database\Seeders;

use App\Models\{User, Petugas, Zona};
use Illuminate\Database\Seeder;

class PetugasSeeder extends Seeder
{
    public function run(): void
    {
        $user  = User::where('role', 'petugas')->first();
        $zona  = Zona::first();

        if ($user) {
            $petugas = Petugas::create([
                'user_id'              => $user->id,
                'nomor_pegawai'        => 'PTG-2024-001',
                'status_ketersediaan'  => 'tersedia',
            ]);

            // Mapping petugas ke zona
            if ($zona) {
                $petugas->zonas()->attach($zona->id);
            }
        }
    }
}
