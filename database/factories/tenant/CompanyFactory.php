<?php

namespace Database\Factories\Tenant;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type_document' => $this->faker->randomElement(['dni', 'ruc', 'none']),
            'number_document' => $this->faker->randomNumber(8),
            'legal_name' => $this->faker->company,
            'commercial_name' => $this->faker->company,
            'logo' => null,
            'status' => $this->faker->randomElement(['active', 'inactive'])
        ];
    }
}
