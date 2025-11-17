<?php

namespace Database\Seeders;

use App\Models\Sales\URS;
use Illuminate\Database\Seeder;

class URSSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        URS::factory()->count(1000)->create();
    }
}
