<?php

namespace App\Models;

use App\Models\Behaviors\HasApiModel;
use App\Models\Behaviors\Transformable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Gallery extends AbstractModel
{
    use HasApiModel;
    use HasFactory;
    use Transformable;

    protected $apiModelClass = \App\Models\Api\Gallery::class;

    protected $fillable = [
        'datahub_id',
        'title',
        'floor',
        'number',
        'is_closed',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'latitude' => 'decimal:13',
        'longitude' => 'decimal:13',
    ];
}
