<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Model;
use App\Models\Behaviors\HasApiRelations;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tour extends Model implements Sortable
{
    use HasApiRelations;
    use HasFactory;
    use HasMedias;
    use HasPosition;
    use HasRevisions;
    use HasTranslation;

    protected $fillable = [
        'duration',
        'gallery_id',
        'position',
        'publish_end_date',
        'publish_start_date',
        'published',
        'selector_number',
        'sound_id',
    ];

    public $translatedAttributes = [
        'active',
        'description',
        'sound_id',
        'title',
    ];

    protected function durationInMinutes(): Attribute
    {
        return Attribute::make(
            get: fn ($duration, $attributes) => $attributes['duration'] . ' minutes',
        );
    }

    public function audio()
    {
        return $this->belongsToApi(\App\Models\Api\Sound::class, 'sound_id');
    }

    public function gallery()
    {
        return $this->belongsToApi(\App\Models\Api\Gallery::class, 'gallery_id');
    }

    public function stops()
    {
        return $this->hasMany(TourStop::class);
    }
}
