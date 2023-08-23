<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Model;
use App\Models\Behaviors\HasApiRelations;

class TourStop extends Model implements Sortable
{
    use HasApiRelations;
    use HasPosition;
    use HasRevisions;
    use HasTranslation;

    protected $fillable = [
        'artwork_id',
        'position',
        'tour_id',
    ];

    public $translatedAttributes = [
        'active',
        'sound_id',
        'title',
        'transcript',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function audio()
    {
        return $this->belongsToApi(Api\Sound::class, 'sound_id');
    }

    public function object()
    {
        return $this->belongsToApi(Api\Artwork::class, 'artwork_id');
    }
}
