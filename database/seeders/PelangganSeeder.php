<?php
// TANGGUNG JAWAB: Arthur Budi Maharesi (PBI-01)
namespace Database\Seeders;

use App\Models\{User, Pelanggan, Zona};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PelangganSeeder extends Seeder
{
    public function run(): void
    {
        $zona = Zona::first();
        $user = User::where('role', 'masyarakat')->first();

        if ($user && $zona) {
            Pelanggan::create([
                'user_id'          => $user->id,
                'nama_pelanggan'   => $user->name ?? 'Masyarakat Dummy',
                'nomor_sambungan'  => 'PDAM-2024-0001',
                'alamat'           => 'Jl. Contoh No. 1, RT 01/RW 01',
                'zona_id'          => $zona->id,
                'no_telepon'       => '08123456789',
                'is_active'        => true,
            ]);
        }
    }
}
