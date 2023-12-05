<?php

namespace Database\Factories;

use App\Models\Floor;
use Illuminate\Database\Eloquent\Factories\Factory;

class FloorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'active' => true,
            'geo_id' => fake()->unique()->randomElement(Floor::LEVELS),
            'level' => fake()->unique()->randomElement(array_keys(Floor::LEVELS)),
            'title' => function ($attributes) {
                return fake()->word() . ' '  . $attributes['level'];
            },
        ];
    }
}
