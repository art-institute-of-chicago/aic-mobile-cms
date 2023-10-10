<?php

namespace App\Models;

use App\Models\Behaviors\HasApiModel;
use App\Models\Behaviors\Transformable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Gallery extends AbstractModel
{
    use HasApiModel;
    use HasFactory;
    use Transformable;

    protected $apiModelClass = \App\Models\Api\Gallery::class;

    protected $fillable = [
        'datahub_id',
        'title',
        'floor',
        'number',
        'is_closed',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'latlng' => 'array',
        'latitude' => 'decimal:13',
        'longitude' => 'decimal:13',
    ];

    protected $appends = [
        'latlng',
    ];

    public $slugAttributes = [
        'title',
    ];

    /**
     * A custom attribute that returns JSON, used for the Map field on the form.
     * It is derived from the `latlon` attribute from the API resource.
     */
    public function latlng(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes): array {
                return [
                    'address' => '111 South Michigan Avenue, Chicago, IL 60603',
                    'latlng' => Str::replace(',', '|', $attributes['latlon']),
                    'boundingBox' => [
                        'north' => 41.88085384238198,
                        'south' => 41.8783542495326,
                        'east' => -87.6208768609257,
                        'west' => -87.62429309521068,
                    ],
                ];
            },
        );
    }

    public function latlngstring(): Attribute
    {
        return Attribute::make(
            get: fn () => json_encode($this->latlng),
        );
    }
}
