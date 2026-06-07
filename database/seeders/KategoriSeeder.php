<?php
// TANGGUNG JAWAB: Arthur Budi Maharesi (PBI-02)
namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            ['kode_kategori' => 'AMT-01', 'nama_kategori' => 'Air Mati',   'sla_jam' => 12, 'deskripsi' => 'Tidak ada aliran air sama sekali'],
            ['kode_kategori' => 'AK-01',  'nama_kategori' => 'Air Keruh',  'sla_jam' => 24, 'deskripsi' => 'Air berwarna keruh atau kotor'],
            ['kode_kategori' => 'AM-02',  'nama_kategori' => 'Air Macet',  'sla_jam' => 12, 'deskripsi' => 'Aliran air terhenti atau sangat lambat'],
            ['kode_kategori' => 'AB-03',  'nama_kategori' => 'Air Berbau', 'sla_jam' => 48, 'deskripsi' => 'Air mengeluarkan bau tidak sedap'],
        ];

        foreach ($kategoris as $k) {
            Kategori::updateOrCreate(
                ['kode_kategori' => $k['kode_kategori']],
                [...$k, 'is_active' => true]
            );
        }
    }
}
