<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudioLog>
 */
class StudioLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Tentukan studio_id secara acak (1 atau 2)
        $studio_id = $this->faker->randomElement([1, 2]);

        // Tentukan harga berdasarkan studio_id
        $hargaPerJam = ($studio_id == 1) ? 35000 : 40000;

        // Buat jumlah jam acak antara 1 s.d. 5 jam
        $jumlah_jam = $this->faker->numberBetween(1, 5);

        return [
            'studio_id' => $studio_id,
            // Buat tanggal acak dalam 1 tahun terakhir
            'tanggal' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'jumlah_jam' => $jumlah_jam,
            'total_pendapatan' => $jumlah_jam * $hargaPerJam,
        ];
    }
}