<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AudioFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => fake()->unique()->randomNumber(nbDigits: 5),
            'locale' => config('app.locale'),
            'title' => fake()->words(5, asText: true),
            'content' => fake()->url(),
            'transcript' => fake()->words(100, asText: true),
        ];
    }

    public function translated(): Factory
    {
        return $this->state(function ($attributes) {
            return [
                'locale' =>
                    fake()->randomElement(array_diff(config('translatable.locales'), [config('app.locale')])),
            ];
        });
    }
}
