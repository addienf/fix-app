<?php

namespace Database\Seeders;

use App\Models\Sales\SpesifikasiProducts\SpesifikasiProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpecProSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        SpesifikasiProduct::factory()->count(1000)->create();
    }
}
