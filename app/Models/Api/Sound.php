<?php

namespace App\Models\Api;

use App\Helpers\StringHelpers;
use App\Libraries\Api\Models\BaseApiModel;

class Sound extends BaseApiModel
{
    protected array $endpoints = [
        'collection' => '/api/v1/sounds',
        'resource' => '/api/v1/sounds/{id}',
        'search' => '/api/v1/sounds/search',
    ];

    protected $augmentedModelClass = \App\Models\Sound::class;

    public function getTypeAttribute()
    {
        return 'sound';
    }

    public function __toString(): string
    {
        return $this->title;
    }
}
