<?php

namespace Database\Factories;

use App\Models\Mobil;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mobil>
 */
class MobilFactory extends Factory
{
    protected $model = Mobil::class;

    /**
     * Definisi data mobil acak (dipakai untuk testing).
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->randomElement(['Avanza', 'Xpander', 'Brio', 'Ertiga', 'Fortuner', 'Civic', 'Ioniq 5']).' '.fake()->randomElement(['G', 'RS', 'Ultimate', 'VRZ', 'Signature']),
            'brand' => fake()->randomElement(['Toyota', 'Honda', 'Mitsubishi', 'Suzuki', 'Hyundai', 'Daihatsu']),
            'harga' => fake()->numberBetween(150, 1500) * 1_000_000,
            'tahun' => fake()->numberBetween(2018, (int) date('Y')),
            'transmisi' => fake()->randomElement(['Manual', 'Matic', 'CVT']),
            'bahan_bakar' => fake()->randomElement(['Bensin', 'Diesel', 'Hybrid', 'Listrik']),
            'status' => fake()->randomElement(['tersedia', 'sold']),
            'foto' => 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341',
        ];
    }
}
