<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StudioLog; // <-- Jangan lupa tambahkan ini

class StudioLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat 100 data dummy menggunakan StudioLogFactory
        StudioLog::factory()->count(100)->create();
    }
}