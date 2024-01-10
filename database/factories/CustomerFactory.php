<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type_document' => $this->faker->randomElement(['dni']),
            'number_document' => $this->faker->randomNumber(8),
            'last_name' => $this->faker->lastName,
            'first_name' => $this->faker->firstName,
            'birth_date' => $this->faker->date,
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'phone' => $this->faker->phoneNumber,
            'company_id' => function () {
                return Company::inRandomOrder()->first()->id;
            },
            'status' => $this->faker->randomElement(['active', 'inactive'])
        ];
    }
}
