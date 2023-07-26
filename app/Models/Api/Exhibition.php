<?php

namespace App\Models\Api;

use App\Helpers\StringHelpers;
use App\Libraries\Api\Models\BaseApiModel;

class Exhibition extends BaseApiModel
{
    protected array $endpoints = [
        'collection' => '/api/v1/exhibitions',
        'resource' => '/api/v1/exhibitions/{id}',
        'search' => '/api/v1/exhibitions/search',
    ];

    protected $augmentedModelClass = \App\Models\Exhibition::class;

    protected array $casts = [
        'aic_start_at' => 'date',
        'aic_end_at' => 'date'
    ];

    public function getTypeAttribute()
    {
        return 'exhibition';
    }

    public function getTitleSlugAttribute()
    {
        return StringHelpers::getUtf8Slug($this->title);
    }

    /**
     * The mobile app data only includes exhibitions that have started, are
     * featured, and are not closed.
     *
     * See https://github.com/art-institute-of-chicago/aic-mobile-cms/blob/main/sites/all/modules/custom/aicapp/includes/aicapp.admin.inc#L827-L859
     */
    public function scopeStartedFeaturedAndNotClosed($query)
    {
        return $query
            ->rawSearch([
                'bool' => [
                    'must' => [
                        [
                            'range' => [
                                'aic_start_at' => [
                                    'lte' => 'now',
                                ],
                            ],
                        ],
                        [
                            'term' => [
                                'is_featured' => true,
                            ]
                        ],
                    ],
                    'must_not' => [
                        'term' => [
                            'status' => 'Closed'
                        ],
                    ],
                ]
            ])
            ->orderBy('aic_start_at')
            ->orderBy('aic_end_at');
    }
}
