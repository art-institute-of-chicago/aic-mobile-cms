<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnnotationCategory extends Model
{
    use HasTranslation;

    const TITLES = [
        'Amenity',
        'Area',  // Formerly 'Text'
        'Department',
    ];

    public $fillable = [
        'id',
    ];

    public $translatedAttributes = [
        'active',
        'title',
    ];

    public function types(): HasMany
    {
        return $this->hasMany(AnnotationType::class);
    }
}
