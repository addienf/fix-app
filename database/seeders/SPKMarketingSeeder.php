<?php

namespace Database\Seeders;

use App\Models\Sales\SPKMarketings\SPKMarketing;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SPKMarketingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        SPKMarketing::factory()->count(1000)->create();
    }
}
