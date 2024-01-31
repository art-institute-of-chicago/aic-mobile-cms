<?php

namespace Database\Factories;

use A17\Twill\Models\File;
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

    public function withFloorPlan(): Factory
    {
        return $this->state(fn () => [])
            ->afterCreating(function (Floor $floor) {
                $filename = 'test.pdf';
                $file = File::create([
                    'filename' => $filename,
                    'uuid' => fake()->uuid() . '/' . $filename,
                    'size' => fake()->randomNumber(),
                ]);
                $floor->files()->attach($file, ['locale' => 'en', 'role' => 'floor_plan']);
                $floor->save();
            });
    }
}
