<?php

namespace Database\Factories\Api;

use App\Models\Api\Gallery;

class GalleryFactory extends ApiFactory
{
    public $model = Gallery::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->randomNumber(nbDigits: 3),
            'title' => ucfirst($this->faker->words(nb: 5, asText: true)),
        ];
    }
}
