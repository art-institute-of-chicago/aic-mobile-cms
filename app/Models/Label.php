<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Label extends Model
{
    use HasFactory;
    use HasTranslation;

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
