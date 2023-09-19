<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Model;

class LoanObject extends Model
{
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
}
