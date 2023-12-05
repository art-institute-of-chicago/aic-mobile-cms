<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Annotation>
 */
class AnnotationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'active' => true,
            'label' => ucfirst(fake()->words(nb: 2, asText: true)),
            'description' => fake()->sentences(asText: true),
            'latitude' => fake()->randomFloat(13, -90, 90),
            'longitude' => fake()->randomFloat(13, -80, 80),
        ];
    }
}
