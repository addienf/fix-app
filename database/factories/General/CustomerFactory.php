<?php

namespace Database\Factories\General;

use App\Models\General\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'phone_number' => $this->faker->phoneNumber(),
            'department' => $this->faker->jobTitle(),
            'company_name' => $this->faker->company(),
            'company_address' => $this->faker->address(),
        ];
    }
}
