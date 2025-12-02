<?php

namespace Database\Factories\Sales;

use App\Models\General\Customer;
use App\Models\Sales\URS;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sales\URS>
 */
class URSFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    private static int $counter = 1;

    public function definition(): array
    {
        $num = str_pad(self::$counter, 3, '0', STR_PAD_LEFT);
        self::$counter++; // increment setiap create dipanggil

        $month = now()->format('m');
        $year  = now()->format('y');

        $noUrs = "$num/QKS/MKT/URS/$month/$year";

        return [
            'no_urs' => $noUrs,
            'customer_id' => Customer::factory(),
            'permintaan_khusus' => $this->faker->sentence(),
        ];
    }
}
