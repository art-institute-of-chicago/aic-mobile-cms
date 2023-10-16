<?php

namespace App\Models;

use App\Libraries\Api\Builders\Relations\NullRelation;
use App\Models\Behaviors\HasApiModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Audio extends AbstractModel
{
    use HasApiModel {
        getTitleAttribute as getTitle;
    }
    use HasFactory;

    protected $apiModelClass = \App\Models\Api\Audio::class;

    protected $table = 'audios';

    protected $fillable = [
        'datahub_id',
        'title',
        'content',
        'locale',
        'transcript',
    ];

    protected $appends = [
        'title_markup',
    ];

    public function getTitleAttribute()
    {
        return strip_tags($this->getTitle());
    }

    public function getTitleMarkupAttribute()
    {
        return $this->getTitle();
    }

    public function setTitleMarkupAttribute($title)
    {
        return $this->attributes['title'] = $title;
    }

    public function apiRelation(): HasOne
    {
        return $this->hasOne(ApiRelation::class, 'datahub_id', 'datahub_id');
    }

    /**
     * If the API relatable model (either a Stop or a Tour) exists, return that
     * relationship; otherwise, return a null relationship.
     */
    public function selector(): MorphTo|NullRelation
    {
        return $this->apiRelation?->morph?->relatable() ?? new NullRelation($this->newQuery(), $this);
    }
}
