<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TourFactory extends Factory
{
    public function definition(): array
    {
        return [
            'active' => true, // Simulates an active translation
            'description' => fake()->words(100, asText: true),
            'duration' => fake()->numberBetween(10, 60),
            'featured' => fake()->boolean(),
            'intro' => fake()->words(100, asText: true),
            'position' => fake()->unique()->numberBetween(0, 20),
            'published' => fake()->boolean(),
            'title' => fake()->words(5, asText: true),
        ];
    }
}
