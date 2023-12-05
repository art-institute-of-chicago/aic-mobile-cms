<?php

namespace Database\Factories;

use App\Models\Gallery;
use Illuminate\Database\Eloquent\Factories\Factory;

class CollectionObjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => fake()->unique()->randomNumber(nbDigits: 5),
            'datahub_id' => fake()->unique()->randomNumber(nbDigits: 5),
            'title' => fake()->words(5, asText: true),
            'artist_display' => fake()->name(),
            'is_on_view' => fake()->boolean(),
            'credit_line' => fake()->sentence(),
            'copyright_notice' => fake()->words(10, asText: true),
            'latitude' => fake()->randomFloat(nbMaxDecimals: 13, min: -90, max: 90),
            'longitude' => fake()->randomFloat(nbMaxDecimals: 13, min: -180, max: 180),
            'gallery_id' => Gallery::factory(),
        ];
    }
}
