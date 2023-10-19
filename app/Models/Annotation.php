<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Model;
use App\Models\AnnotationType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;

class Annotation extends Model
{
    use HasMedias;
    use HasTranslation;

    public $mediasParams = [
        'upload' => [
            'default' => [
                [
                    'name' => 'default',
                    'ratio' => 'default',
                ],
            ]
        ],
    ];

    protected $fillable = [
        'latitude',
        'longitude',
    ];

    public $translatedAttributes = [
        'active',
        'label',
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
}
