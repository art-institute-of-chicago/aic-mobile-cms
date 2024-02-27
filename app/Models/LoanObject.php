<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasMedias;
use App\Models\Behaviors\HasApiRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class LoanObject extends AbstractModel
{
    use HasApiRelations;
    use HasFactory;
    use HasMedias;

    protected $fillable = [
        'artist_display',
        'copyright_notice',
        'credit_line',
        'gallery_id',
        'is_on_view',
        'latitude',
        'longitude',
        'main_reference_number',
        'title',
    ];

    protected $attributes = [
        'is_on_view' => true,
    ];

    public function gallery(): BelongsTo
    {
        return $this->belongsToApi(\App\Models\Api\Gallery::class, 'gallery_id');
    }

    public function selectors(): MorphMany
    {
        return $this->morphMany(Selector::class, 'object');
    }
}
