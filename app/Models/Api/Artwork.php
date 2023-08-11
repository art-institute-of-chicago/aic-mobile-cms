<?php

namespace App\Models\Api;

use App\Helpers\StringHelpers;
use App\Libraries\Api\Models\BaseApiModel;

class Artwork extends BaseApiModel
{
    protected array $endpoints = [
        'collection' => '/api/v1/artworks',
        'resource' => '/api/v1/artworks/{id}',
        'search' => '/api/v1/artworks/search',
    ];

    protected $augmentedModelClass = \App\Models\Artwork::class;

    public function getTypeAttribute()
    {
        return 'artwork';
    }

    public function getTitleSlugAttribute()
    {
        return StringHelpers::getUtf8Slug($this->title);
    }

    public function gallery()
    {
        return $this->belongsToApi(\App\Models\Api\Gallery::class, 'gallery_id');
    }

    public function scopeOnView($query)
    {
        return $query
            ->rawSearch([
                'bool' => [
                    'must' => [
                        ['term' => ['is_on_view' => true]],
                    ],
                ]
            ]);
    }
}
