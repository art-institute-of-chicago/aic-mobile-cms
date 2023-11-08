<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasBlocks;
use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Behaviors\HasTranslation;
use App\Models\Behaviors\HasApiModel;
use App\Models\Behaviors\HasApiRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class CollectionObject extends AbstractModel
{
    use HasApiModel;
    use HasApiRelations;
    use HasBlocks;
    use HasFactory;
    use HasMedias;
    use HasTranslation;

    protected $apiModelClass = \App\Models\Api\CollectionObject::class;

    protected $fillable = [
        'datahub_id',
        'title',
        'artist_display',
        'is_on_view',
        'credit_line',
        'copyright_notice',
        'latitude',
        'longitude',
        'image_id',
        'gallery_id',
    ];

    protected $casts = [
        'latitude' => 'decimal:13',
        'longitude' => 'decimal:13',
    ];

    public function gallery(): BelongsTo
    {
        return $this->belongsToApi(\App\Models\Api\Gallery::class, 'gallery_id');
    }
}
