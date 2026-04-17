<?php
// TANGGUNG JAWAB: Farisha Huwaida Shofha (PBI-16)
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Admin SIGAP-AIR',  'email' => 'admin@sigapair.test',       'username' => 'admin',       'role' => 'admin'],
            ['name' => 'Dewi Supervisor',  'email' => 'supervisor@sigapair.test',   'username' => 'supervisor',  'role' => 'supervisor'],
            ['name' => 'Roni Petugas',     'email' => 'petugas@sigapair.test',      'username' => 'petugas',     'role' => 'petugas'],
            ['name' => 'Budi Masyarakat',  'email' => 'masyarakat@sigapair.test',   'username' => 'masyarakat',  'role' => 'masyarakat'],
        ];

        foreach ($users as $user) {
            User::create([
                ...$user,
                'password'  => Hash::make('password'),
                'no_telepon' => '081234567890',
                'is_active' => true,
            ]);
        }
    }
}
