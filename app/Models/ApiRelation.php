<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;

class ApiRelation extends AbstractModel
{
    protected $fillable = [
        'datahub_id',
    ];

    public function morph(): HasOne
    {
        return $this->hasOne(ApiRelatable::class);
    }
}
