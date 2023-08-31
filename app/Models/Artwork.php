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

class Artwork extends AbstractModel
{
    use HasApiModel;
    use HasApiRelations;
    use HasBlocks;
    use HasFactory;
    use HasMedias;
    use HasTranslation;

    protected $apiModelClass = \App\Models\Api\Artwork::class;

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

    protected $appends = [
        'latlng',
        'image_url',
        // 'gallery_location',
    ];

    /**
     * TODO: Implement Images from API
     */
    public function getImageUrlAttribute(): string
    {
        return '';
    }

    /**
     * TODO: Does this belong in a serializer?
     */
    public function getGalleryLocationAttribute(): ?string
    {
        return $this->gallery?->title;
    }

    /**
     * A custom attribute that returns JSON, used for the Map field on the form.
     * It is derived from the `latlon` attribute from the API resource.
     */
    public function getLatlngAttribute(): string
    {
        return json_encode([
            'latlng' => Str::replace(',', '|', $this->latlon),
        ]);
    }

    public function gallery(): BelongsTo
    {
        return $this->belongsToApi(\App\Models\Api\Gallery::class, 'gallery_id');
    }
}
