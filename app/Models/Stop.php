<?php

namespace App\Models;

use A17\Twill\Models\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Stop extends Model
{
    use HasFactory;

    protected $fillable = [
        'publish_end_date',
        'publish_start_date',
        'published',
    ];

    protected $casts = [
        'publish_end_date' => 'datetime',
        'publish_start_date' => 'datetime',
    ];

    protected $appends = [
        'title',
    ];

    public function title(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->selector?->object->title,
        );
    }

    public function objectId(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->selector?->object->datahub_id ?? $this->selector?->object->id,
        );
    }

    public function selector(): MorphOne
    {
        return $this->morphOne(Selector::class, 'selectable');
    }

    public function tours(): BelongsToMany
    {
        return $this->belongsToMany(Tour::class, 'tour_stops');
    }
}
