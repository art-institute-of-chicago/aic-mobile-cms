<?php

namespace App\Models;

use App\Models\Behaviors\HasApiModel;
use App\Models\Behaviors\HasMedias;
use App\Models\Behaviors\Transformable;
use App\Helpers\StringHelpers;

class Exhibition extends AbstractModel
{
    use HasApiModel;
    use HasMedias;
    use Transformable;

    protected $apiModel = \App\Models\Api\Exhibition::class;

    protected $fillable = [
        'datahub_id',
        'title',
        'image_url',
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
