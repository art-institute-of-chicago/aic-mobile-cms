<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LabelFactory extends Factory
{
    public function definition(): array
    {
        return [
            'active' => true, // Simulates an active translation
            'key' => fake()->unique()->word(),
            'text' => ucfirst(fake()->words(10, asText: true)),
        ];
    }
}
