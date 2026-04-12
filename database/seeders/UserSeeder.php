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
            ['name' => 'Admin SIGAP-AIR',  'email' => 'admin@sigapair.test',       'role' => 'admin'],
            ['name' => 'Dewi Supervisor',  'email' => 'supervisor@sigapair.test',   'role' => 'supervisor'],
            ['name' => 'Roni Petugas',     'email' => 'petugas@sigapair.test',      'role' => 'petugas'],
            ['name' => 'Budi Masyarakat',  'email' => 'masyarakat@sigapair.test',   'role' => 'masyarakat'],
        ];

        foreach ($users as $user) {
            User::create([
                ...$user,
                'password'  => Hash::make('password'),
                'is_active' => true,
            ]);
        }
    }
}
