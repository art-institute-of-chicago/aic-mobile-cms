<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasPosition;
use A17\Twill\Models\Behaviors\Sortable;
use A17\Twill\Models\Model;
use App\Models\Behaviors\HasApiRelations;

class Stop extends Model implements Sortable
{
    use HasApiRelations;
    use HasPosition;

    protected $fillable = [
        'title',
        'position',
        'artwork_id',
        'sound_id',
    ];

    public function audio()
    {
        return $this->belongsToApi(\App\Models\Api\Sound::class, 'sound_id');
    }

    public function object()
    {
        return $this->belongsToApi(\App\Models\Api\Artwork::class, 'artwork_id');
    }
}
