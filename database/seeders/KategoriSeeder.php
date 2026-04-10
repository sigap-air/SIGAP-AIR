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
            ['nama_kategori' => 'Air Keruh',       'sla_jam' => 24, 'deskripsi' => 'Air berwarna keruh atau kotor'],
            ['nama_kategori' => 'Air Berbau',       'sla_jam' => 24, 'deskripsi' => 'Air mengeluarkan bau tidak sedap'],
            ['nama_kategori' => 'Air Tidak Mengalir','sla_jam' => 12,'deskripsi' => 'Tidak ada aliran air sama sekali'],
            ['nama_kategori' => 'Tekanan Air Lemah','sla_jam' => 48, 'deskripsi' => 'Aliran air sangat kecil/lemah'],
            ['nama_kategori' => 'Pipa Bocor',       'sla_jam' => 6,  'deskripsi' => 'Ada kebocoran pada pipa distribusi'],
        ];

        foreach ($kategoris as $k) {
            Kategori::create([...$k, 'is_active' => true]);
        }
    }
}
