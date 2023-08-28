<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasRelated;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Model;
use App\Models\Behaviors\HasApiRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stop extends Model
{
    use HasApiRelations;
    use HasFactory;
    use HasRelated;
    use HasRevisions;
    use HasTranslation;

    protected $fillable = [
        'artwork_id',
        'publish_end_date',
        'publish_start_date',
        'published',
        'selector_number',
    ];

    public $casts = [
        'publish_end_date' => 'datetime',
        'publish_start_date' => 'datetime',
    ];

    public $translatedAttributes = [
        'active',
        'title',
    ];

    public function audios()
    {
        return $this->apiElements();
    }

    public function object()
    {
        return $this->belongsToApi(Api\Artwork::class, 'artwork_id');
    }

    public function tours()
    {
        return $this->belongsToMany(Tour::class, 'tour_stops');
    }
}
