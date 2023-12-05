<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LoanObjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->words(asText: true),
            'is_on_view' => fake()->boolean(),
        ];
    }
}
