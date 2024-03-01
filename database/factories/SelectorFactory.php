<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SelectorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'number' => fake()->unique()->randomNumber(nbDigits: 3),
            'published' => true,
        ];
    }
}
