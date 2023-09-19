<?php

namespace App\Models\Api;

use App\Libraries\Api\Models\BaseApiModel;

class Audio extends BaseApiModel
{
    protected array $endpoints = [
        'collection' => '/api/v1/sounds',
        'resource' => '/api/v1/sounds/{id}',
        'search' => '/api/v1/sounds/search',
    ];

    protected $augmentedModelClass = \App\Models\Audio::class;

    public function getTypeAttribute()
    {
        return 'sound';
    }

    public function getLocaleAttribute()
    {
        return $this->getAugmentedModel()?->locale;
    }

    public function getTranscriptAttribute()
    {
        return $this->getAugmentedModel()?->transcript;
    }

    public function __toString(): string
    {
        return $this->title;
    }
}
