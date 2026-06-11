<?php

namespace Database\Factories;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

class KategoriFactory extends Factory
{
    protected $model = Kategori::class;

    public function definition(): array
    {
        return [
            'nama_kategori' => $this->faker->randomElement([
                'Air Keruh', 'Air Bau', 'Tidak Mengalir', 'Tekanan Lemah', 'Pipa Bocor', 'Meter Rusak'
            ]) . ' ' . $this->faker->unique()->randomNumber(3),
            'kode_kategori' => 'KAT_' . str_replace('.', '_', uniqid('', true)),
            'deskripsi'     => $this->faker->sentence(),
            'sla_jam'       => $this->faker->randomElement([12, 24, 48, 72]),
            'is_active'     => true,
        ];
    }
}
