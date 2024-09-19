<?php

namespace Database\Factories\System;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory {

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {

        return [
            'document_type' => $this->faker->randomElement(['dni', 'ruc', 'none']),
            'document_number' => $this->faker->randomNumber(8),
            'legal_name' => $this->faker->company,
            'commercial_name' => $this->faker->company
        ];

    }

}
