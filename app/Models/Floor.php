<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasFiles;
use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Model;

class Floor extends Model
{
    use HasFiles;
    use HasTranslation;

    /**
     * Mapping of 'level' => 'geo_id'
     *
     * The geo_id is a reference to the geojson data for the map.
     */
    const LEVELS = [
        'LL' => '0002',
        '1' => '0003',
        '2' => '0004',
        '3' => '0005',
    ];

    const ANCHOR_LOCATIONS = [
        '41.88002009571711,-87.62398928403854',
        '41.8800240897643,-87.62334823608397',
    ];

    const ANCHOR_PIXELS = [
        '855.955,1338.365',
        '1011.94,1338.365',
    ];

    public $filesParams = [
        'floor_plan'
    ];

    protected $fillable = [
        'geo_id',
        'level',
    ];

    public $translatedAttributes = [
        'active',
        'title',
    ];

    protected $visible = [
        'geo_id',
        'level',
        'title',
    ];
}
