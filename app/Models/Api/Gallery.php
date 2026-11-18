<?php

namespace App\Models\Api;

use Aic\Hub\Foundation\Library\Api\Models\BaseApiModel;
use Database\Factories\Api\HasApiFactory;
use Illuminate\Database\Eloquent\Builder;

class Gallery extends BaseApiModel
{
    use HasApiFactory;

    protected array $endpoints = [
        'collection' => '/api/v1/galleries',
        'resource' => '/api/v1/galleries/{id}',
        'search' => '/api/v1/galleries/search',
    ];

    protected $augmented = true;
    protected $augmentedModelClass = \App\Models\Gallery::class;

    public function getTypeAttribute()
    {
        return 'gallery';
    }

    public function scopeByTitle(Builder $query, string $title): Builder
    {
        return $query->rawSearch(['bool' => ['must' => ['term' => ['title' => $title]]]]);
    }

    public function __toString(): string
    {
        return "Floor $this->floor, Room $this->number: $this->title";
    }
}
