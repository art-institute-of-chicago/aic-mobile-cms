<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\HasRelated;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Model;
use App\Models\Behaviors\HasApiRelations;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;

class Tour extends Model implements Sortable
{
    use HasApiRelations;
    use HasFactory;
    use HasMedias;
    use HasPosition;
    use HasRelated;
    use HasRevisions;
    use HasTranslation;

    protected $fillable = [
        'duration',
        'gallery_id',
        'position',
        'publish_end_date',
        'publish_start_date',
        'published',
    ];

    public $translatedAttributes = [
        'active',
        'description',
        'intro',
        'title',
        'title_markup',
    ];

    protected function durationInMinutes(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getAttribute('duration') . ' minutes',
        );
    }

    protected function truncatedTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => Str::words($this->getAttribute('title'), 3)
        );
    }

    public function gallery(): BelongsTo
    {
        return $this->belongsToApi(\App\Models\Api\Gallery::class, 'gallery_id');
    }

    public function selector(): MorphOne
    {
        return $this->morphOne(Selector::class, 'selectable');
    }

    public function stops(): BelongsToMany
    {
        return $this->belongsToMany(Stop::class, 'tour_stops')->orderByPivot('position');
    }
}
