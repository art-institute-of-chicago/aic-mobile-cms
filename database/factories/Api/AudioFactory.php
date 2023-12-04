<?php

namespace Database\Factories\Api;

use App\Models\Api\Audio;

class AudioFactory extends ApiFactory
{
    public $model = Audio::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'title' => ucfirst($this->faker->words(nb: 5, asText: true)),
        ];
    }
}
