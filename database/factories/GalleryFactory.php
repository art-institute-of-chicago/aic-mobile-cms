<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GalleryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => fake()->unique()->randomNumber(nbDigits: 3),
            'datahub_id' => fake()->unique()->randomNumber(nbDigits: 5),
            'title' => fake()->words(5, asText: true),
            'floor' => fake()->randomElement(['LL', '1', '2', '3']),
            'number' => fake()->randomNumber(3, strict: true),
            'is_closed' => fake()->boolean(),
            'latitude' => fake()->randomFloat(nbMaxDecimals: 13, min: -90, max: 90),
            'longitude' => fake()->randomFloat(nbMaxDecimals: 10, min: -180, max: 180),
        ];
    }
}
