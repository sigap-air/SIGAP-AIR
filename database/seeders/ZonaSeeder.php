<?php
// TANGGUNG JAWAB: Arthur Budi Maharesi (PBI-03)
namespace Database\Seeders;

use App\Models\Zona;
use Illuminate\Database\Seeder;

class ZonaSeeder extends Seeder
{
    public function run(): void
    {
        $zonas = [
            ['nama_zona' => 'Zona A — Pusat',    'deskripsi' => 'Wilayah pusat kota'],
            ['nama_zona' => 'Zona B — Utara',    'deskripsi' => 'Wilayah bagian utara'],
            ['nama_zona' => 'Zona C — Selatan',  'deskripsi' => 'Wilayah bagian selatan'],
            ['nama_zona' => 'Zona D — Timur',    'deskripsi' => 'Wilayah bagian timur'],
            ['nama_zona' => 'Zona E — Barat',    'deskripsi' => 'Wilayah bagian barat'],
        ];

        foreach ($zonas as $z) {
            Zona::create([...$z, 'is_active' => true]);
        }
    }
}
