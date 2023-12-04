<?php

namespace Database\Factories\Api;

use App\Models\Api\CollectionObject;

class CollectionObjectFactory extends ApiFactory
{
    public $model = CollectionObject::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->randomNumber(nbDigits: 5),
            'title' => ucfirst($this->faker->words(nb: 5, asText: true)),
            'image_id' => fake()->uuid(),
        ];
    }
}
