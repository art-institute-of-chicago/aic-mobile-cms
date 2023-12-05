<?php

namespace App\Models\Api;

use App\Libraries\Api\Models\BaseApiModel;
use App\Models\Behaviors\HasMediasApi;
use Database\Factories\Api\HasApiFactory;
use Illuminate\Database\Eloquent\Builder;

class CollectionObject extends BaseApiModel
{
    use HasApiFactory;
    use HasMediasApi;

    protected array $endpoints = [
        'collection' => '/api/v1/artworks',
        'resource' => '/api/v1/artworks/{id}',
        'search' => '/api/v1/artworks/search',
    ];

    protected $augmentedModelClass = \App\Models\CollectionObject::class;

    public $mediasParams = [
        'iiif' => [
            'default' => [
                [
                    'name' => 'full',
                    'height' => 800,
                    'width' => 800,
                ],
            ],
            'thumbnail' => [
                [
                    'name' => 'thumbnail',
                    'height' => 112,
                    'width' => 112,
                ],
            ],
        ],
    ];

    public function getTypeAttribute()
    {
        return 'artwork';
    }

    public function scopeOnView(Builder $query): Builder
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

    public function scopeBySoundIds(Builder $query, array $soundIds): Builder
    {
        $matches = [];
        foreach ($soundIds as $soundId) {
            $matches['match'] = ['sound_ids' => $soundId];
        }
        return $query
            ->rawSearch([
                'bool' => [
                    'must' => $matches,
                ]
            ]);
    }

    public function scopeMostViewed(Builder $query): Builder
    {
        return $query
            ->rawSearch([
                'bool' => [
                    'must' => [
                        ['term' => ['is_boosted' => true]],
                    ],
                ],
            ])
            ->orderBy('pageviews', 'desc')
            ->limit(8);
    }

    public function __toString(): string
    {
        return "$this->title - $this->artist_display";
    }
}
