<?php

namespace App\Models;

use App\Helpers\StringHelpers;
use App\Models\Behaviors\HasApiModel;
use App\Models\Behaviors\HasMedias;
use App\Models\Behaviors\Transformable;

class Exhibition extends AbstractModel
{
    use HasApiModel;
    use HasMedias;
    use Transformable;

    protected $apiModelClass = \App\Models\Api\Exhibition::class;

    protected $fillable = [
        'datahub_id',
        'title',
        'image_url',
        'is_featured',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    public $slugAttributes = [
        'title',
    ];

    public $mediasParams = [
        'hero' => [
            'default' => [
                [
                    'name' => 'default',
                    'ratio' => 16 / 9,
                ],
            ],
        ],
    ];

    public function getSlugAttribute()
    {
        return ['en' => StringHelpers::getUtf8Slug($this->title)];
    }
}
