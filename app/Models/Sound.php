<?php

namespace App\Models;

use App\Models\Behaviors\HasApiModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sound extends AbstractModel
{
    use HasApiModel;
    use HasFactory;

    protected $apiModelClass = \App\Models\Api\Sound::class;

    protected $fillable = [
        'datahub_id',
        'title',
        'content',
        'locale',
        'transcript',
    ];
}
