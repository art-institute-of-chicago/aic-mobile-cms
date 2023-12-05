<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AnnotationCategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'active' => true,
            'title' => ucfirst(fake()->word()),
        ];
    }
}
