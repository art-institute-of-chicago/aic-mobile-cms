<?php

namespace App\Models;

use App\Models\Behaviors\HasApiModel;
use App\Models\Behaviors\HasMedias;
use App\Models\Behaviors\Transformable;
use App\Helpers\StringHelpers;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Gallery extends AbstractModel
{
    use HasApiModel;
    use HasMedias;
    use Transformable;

    protected $apiModel = \App\Models\Api\Gallery::class;

    protected $fillable = [
        'datahub_id',
    ];

    protected $casts = [
        'latitude' => 'decimal:13',
        'longitude' => 'decimal:13',
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
