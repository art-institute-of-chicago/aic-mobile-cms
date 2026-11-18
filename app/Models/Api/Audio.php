<?php

namespace App\Models\Api;

use Aic\Hub\Foundation\Library\Api\Models\BaseApiModel;
use Database\Factories\Api\HasApiFactory;
use Illuminate\Database\Eloquent\Builder;

class Audio extends BaseApiModel
{
    use HasApiFactory;

    protected array $endpoints = [
        'collection' => '/api/v1/sounds',
        'resource' => '/api/v1/sounds/{id}',
        'search' => '/api/v1/sounds/search',
    ];

    protected $augmented = true;
    protected $augmentedModelClass = \App\Models\Audio::class;

    public function getTypeAttribute()
    {
        return 'sound';
    }

    public function getTitleMarkupAttribute()
    {
        return $this->getAttribute('title');
    }

    public function setTitleMarkupAttribute($title)
    {
        return $this->setAttribute('title', $title);
    }

    public function getLocaleAttribute()
    {
        return $this->getAugmentedModel()?->locale;
    }

    public function getTranscriptAttribute()
    {
        return $this->getAugmentedModel()?->transcript;
    }

    public function scopeByTitle(Builder $query, string $title): Builder
    {
        return $query->rawSearch(['bool' => ['must' => ['term' => ['title' => $title]]]]);
    }

    public function __toString(): string
    {
        return $this->title;
    }
}
