<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SoundFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => fake()->unique()->randomNumber(nbDigits: 5),
            'title' => fake()->words(5, asText: true),
            'content' => fake()->url(),
            'transcript' => fake()->words(100, asText: true),
        ];
    }
}
