<?php

namespace App\Models\Api;

use App\Libraries\Api\Models\BaseApiModel;
use App\Models\Behaviors\HasMediasApi;

class CollectionObject extends BaseApiModel
{
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
                    'name' => 'default',
                    'ratio' => 'default',
                ],
            ]
        ],
    ];

    public function getTypeAttribute()
    {
        return 'artwork';
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

    public function scopeBySoundIds($query, $soundIds)
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

    public function __toString(): string
    {
        return "$this->title - $this->artist_display";
    }
}
