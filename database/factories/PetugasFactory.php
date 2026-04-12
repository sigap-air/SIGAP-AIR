<?php

namespace Database\Factories;

use App\Models\{Petugas, User, Zona};
use Illuminate\Database\Eloquent\Factories\Factory;

class PetugasFactory extends Factory
{
    protected $model = Petugas::class;

    public function definition(): array
    {
        return [
            'user_id'             => User::factory()->state(['role' => 'petugas']),
            'nomor_pegawai'       => 'PTG-' . $this->faker->unique()->numerify('####'),
            'status_ketersediaan' => 'tersedia',
        ];
    }
}
