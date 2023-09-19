<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Model;
use App\Models\Behaviors\HasApiRelations;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanObject extends Model
{
    use HasApiRelations;
    use HasMedias;

    protected $fillable = [
        'artist_display',
        'copyright_notice',
        'credit_line',
        'gallery_id',
        'image',
        'latitude',
        'longitude',
        'main_reference_number',
        'title',
    ];

    public function gallery(): BelongsTo
    {
        return $this->belongsToApi(\App\Models\Api\Gallery::class, 'gallery_id');
    }
}
