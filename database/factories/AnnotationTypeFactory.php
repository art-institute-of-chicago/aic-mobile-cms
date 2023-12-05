<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AnnotationTypeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'active' => true,
            'title' => ucwords(fake()->words(asText: true)),
        ];
    }
}
