<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnnotationType extends Model
{
    use HasTranslation;

    const TITLES = [
        'Audio Guide',
        'Check Room',
        'Department',
        'Dining',
        'Elevator',
        'Family Restroom',
        'Garden',
        'Gift Shop',
        'Landmark',
        'Information',
        'Members Lounge',
        "Men's Room",
        'Space',
        'Tickets',
        'Wheelchair Ramp',
        "Women's Room",
    ];

    protected $fillable = [
        'id',
    ];

    public $translatedAttributes = [
        'active',
        'title',
    ];

    protected $with = [
        'category',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(AnnotationCategory::class, 'annotation_category_id');
    }
}
