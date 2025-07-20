<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'category' => $this->faker->randomElement(['Gold', 'Silver', 'Bronze']),
        ];
    }
}
