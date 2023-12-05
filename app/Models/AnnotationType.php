<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnnotationType extends Model
{
    use HasTranslation;

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
