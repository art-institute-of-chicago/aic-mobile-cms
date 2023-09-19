<?php

namespace App\Models\Api;

use App\Helpers\StringHelpers;
use App\Libraries\Api\Models\BaseApiModel;

class Gallery extends BaseApiModel
{
    protected array $endpoints = [
        'collection' => '/api/v1/galleries',
        'resource' => '/api/v1/galleries/{id}',
        'search' => '/api/v1/galleries/search',
    ];

    protected $augmentedModelClass = \App\Models\Gallery::class;

    public function getTypeAttribute()
    {
        return 'gallery';
    }

    public function __toString(): string
    {
        return "Floor $this->floor, Room $this->number: $this->title";
    }
}
