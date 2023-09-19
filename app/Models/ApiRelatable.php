<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ApiRelatable extends Pivot
{
    protected $table = 'api_relatables';

    public function relatable(): MorphTo
    {
        return $this->morphTo('api_relatable');
    }

    public function apiRelation(): BelongsTo
    {
        return $this->belongsTo(ApiRelation::class);
    }
}
