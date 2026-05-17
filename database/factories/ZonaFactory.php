<?php

namespace Database\Factories;

use App\Models\Zona;
use Illuminate\Database\Eloquent\Factories\Factory;

class ZonaFactory extends Factory
{
    protected $model = Zona::class;

    public function definition(): array
    {
        return [
            'nama_zona' => 'Zona ' . $this->faker->unique()->randomElement(['A','B','C','D','E','F'])
                . ' - ' . $this->faker->city(),
            'kode_zona' => 'ZON_' . str_replace('.', '_', uniqid('', true)),
            'deskripsi' => $this->faker->sentence(),
            'is_active' => true,
        ];
    }
}
