<?php

namespace App\Models;

use App\Models\Behaviors\HasApiModel;
use App\Models\Behaviors\HasMedias;
use App\Models\Behaviors\Transformable;
use App\Helpers\StringHelpers;

class Gallery extends AbstractModel
{
    use HasApiModel;
    use Transformable;
    use HasMedias;

    protected $apiModel = 'App\Models\Api\Gallery';

    protected $fillable = [
        'datahub_id',
        'latitude',
        'longitude',
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
