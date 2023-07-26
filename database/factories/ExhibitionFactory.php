<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\Sequence;

class ExhibitionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => fake()->unique()->randomDigit(),
            'title' => fake()->words(5, true),
            'image_url' => fake()->url(),
            'sort' => fake()->randomDigit(),
        ];
    }

    public function sorted(): Factory
    {
        return $this->state(new Sequence(
            fn (Sequence $sequence) => ['sort' => $sequence->index]
        ));
    }
}
