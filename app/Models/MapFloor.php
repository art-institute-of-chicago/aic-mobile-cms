<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasFiles;
use A17\Twill\Models\Model;

class MapFloor extends Model
{
    use HasFiles;

    public $filesParams = [
        'floor_plan'
    ];

    protected $fillable = [
        'anchor_location_1',
        'anchor_location_2',
        'anchor_pixel_1',
        'anchor_pixel_2',
        'title',
    ];
}
