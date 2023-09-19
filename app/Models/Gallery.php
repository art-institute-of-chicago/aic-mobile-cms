<?php

namespace App\Models;

use App\Models\Behaviors\HasApiModel;
use App\Models\Behaviors\Transformable;
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
    public function getLatlngAttribute(): string
    {
        return json_encode([
            'latlng' => Str::replace(',', '|', $this->latlon),
        ]);
    }
}
