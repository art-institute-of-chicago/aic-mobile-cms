<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Model;
use App\Models\AnnotationType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Arr;

class Annotation extends Model
{
    use HasFactory;
    use HasMedias;
    use HasTranslation;

    protected $fillable = [
        'latitude',
        'longitude',
    ];

    public $translatedAttributes = [
        'active',
        'description',
        'label',
    ];

    protected $casts = [
        'latitude' => 'decimal:13',
        'longitude' => 'decimal:13',
    ];

    public function title(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                $titles = [];
                foreach ($this->types as $type) {
                    $title = [];
                    if ($this->floor) {
                        $title[] = $this->floor->title;
                    }
                    $title[] = $type->title;
                    if ($this->label) {
                        $title[] = $this->label;
                    }
                    $titles[] = Arr::join($title, ' - ');
                }
                return Arr::join($titles, ', ');
            },
        );
    }

    public function floor(): BelongsTo
    {
        return $this->belongsTo(Floor::class);
    }

    public function types(): BelongsToMany
    {
        return $this->belongsToMany(AnnotationType::class);
    }

    public function scopeDistinctByType(Builder $query): Builder
    {
        return $query
            ->rightJoin('annotation_annotation_type', function (JoinClause $join) {
                $join->on('annotations.id', '=', 'annotation_annotation_type.annotation_id');
            })
            ->select('annotations.*', 'annotation_annotation_type.annotation_type_id');
    }
}
