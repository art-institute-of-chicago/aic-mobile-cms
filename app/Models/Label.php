<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class Label extends Model
{
    use HasTranslation;

    public const KEYS = [
        'audio_subtitle',
        'audio_title',
        'gift_shops_text',
        'gift_shops_title',
        'home_member_prompt_text',
        'info_subtitle',
        'info_title',
        'map_subtitle',
        'map_title',
        'members_lounge_text',
        'members_lounge_title',
        'museum_hours',
        'restrooms_subtitle',
        'restrooms_title',
        'see_all_tours_intro',
    ];

    protected $fillable = [
        'key',
    ];

    public $translatedAttributes = [
        'active',
        'text',
    ];

    public function title(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) =>  Str::headline($attributes['key']),
        );
    }

    public function scopeOrderByTitle(Builder $query, string $direction = 'asc'): Builder
    {
        return $query->orderBy('key', $direction);
    }
}
