<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasRelated;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Model;
use App\Models\Behaviors\HasApiRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Stop extends Model
{
    use HasApiRelations;
    use HasFactory;
    use HasRelated;
    use HasRevisions;
    use HasTranslation;

    protected $fillable = [
        'object_id',
        'object_type',
        'publish_end_date',
        'publish_start_date',
        'published',
    ];

    public $casts = [
        'publish_end_date' => 'datetime',
        'publish_start_date' => 'datetime',
    ];

    public $translatedAttributes = [
        'active',
        'title',
        'title_markup',
    ];

    public function object(): BelongsTo|MorphTo
    {
        if ($this->object_type === 'collectionObject') {
            return $this->belongsToApi(Api\CollectionObject::class, 'object_id');
        } else {
            return $this->morphTo();
        }
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
