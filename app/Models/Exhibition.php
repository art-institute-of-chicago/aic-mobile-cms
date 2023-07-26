<?php

namespace App\Models;

use App\Helpers\StringHelpers;
use App\Models\Behaviors\HasApiModel;
use App\Models\Behaviors\HasMedias;
use App\Models\Behaviors\Transformable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Exhibition extends AbstractModel
{
    use HasApiModel;
    use HasFactory;
    use HasMedias;
    use Transformable;

    protected $apiModelClass = \App\Models\Api\Exhibition::class;

    protected $fillable = [
        'datahub_id',
        'title',
        'image_url',
        'is_featured',
        'status',
        'aic_start_at',
        'aic_end_at',
    ];

    protected $casts = [
        'aic_start_at' => 'datetime',
        'aic_end_at' => 'datetime',
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
