<?php

namespace App\Models;

use A17\Twill\Models\Model;
use App\Models\Behaviors\HasApiRelations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Query\JoinClause;

class Selector extends Model
{
    use HasApiRelations;
    use HasFactory;

    protected $fillable = [
        'notes',
        'number',
        'object_id',
        'object_type',
        'published',
    ];

    protected $casts = [
        'number' => 'string',
    ];

    protected $appends = [
        'audio_title',
        'default_audio',
        'object_datahub_id',
        'object_title',
        'tour_title',
    ];

    public function apiAudios(): MorphToMany
    {
        return $this->apiElements()->where('relation', 'apiAudios');
    }

    public function object(): BelongsTo|MorphTo
    {
        if ($this->object_type === 'collectionObject') {
            return $this->belongsToApi(Api\CollectionObject::class, 'object_id');
        } else {
            return $this->morphTo();
        }
    }

    public function selectable(): MorphTo
    {
        return $this->morphTo();
    }

    public function audios(): Attribute
    {
        return Attribute::make(
            get: fn () =>  $this->apiAudios?->map(fn ($audio) => Audio::firstWhere('datahub_id', $audio->datahub_id)),
        );
    }

    public function defaultAudio(): Attribute
    {
        return Attribute::make(
            get: fn (): ?Audio => $this->audios?->firstWhere('locale', config('app.locale')),
        );
    }

    public function number(): Attribute
    {
        return Attribute::make(
            get: fn ($number): string => (string) ($number ?? '--'),
            set: fn ($number) => intval($number) ? $number : null,
        );
    }

    /**
     * Alias of number
     */
    public function title(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->number,
        );
    }

    public function audioTitle(): Attribute
    {
        return Attribute::make(
            get: fn (): string => (string) $this->default_audio?->title,
        );
    }

    public function objectDatahubId(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                $id = null;
                if ($this->object && $this->object_type == 'collectionObject') {
                    $id = $this->object_id;
                } elseif ($this->object) {
                    $id = '(loan)';
                }
                return (string) $id;
            }
        );
    }

    public function objectTitle(): Attribute
    {
        return Attribute::make(
            get: fn (): string => (string) $this->object?->title,
        );
    }

    public function tourTitle(): Attribute
    {
        $selectable = $this->selectable;
        return Attribute::make(
            get: function () use ($selectable): string {
                if ($selectable instanceof Stop) {
                    return (string) $selectable->tours?->pluck('title')->join(', ');
                }
                return (string) $selectable?->title;
            }
        );
    }


    public function scopeOrderByTourTitle(Builder $query, string $direction = 'asc'): Builder
    {
        return $query
            ->select('selectors.*', 'tour_translations.title')
            ->leftJoin('stop_translations', function (JoinClause $join) {
                $join->on('selectors.selectable_id', '=', 'stop_translations.stop_id')
                    ->where('selectors.selectable_type', '=', 'stop')
                    ->where('stop_translations.locale', '=', config('app.locale'));
            })
            ->leftJoin('tour_stops', function (JoinClause $join) {
                $join->on('stop_translations.stop_id', '=', 'tour_stops.stop_id')
                    ->where('selectors.selectable_type', '=', 'stop')
                    ->where('stop_translations.locale', '=', config('app.locale'));
            })
            ->leftJoin('tour_translations', function (JoinClause $join) {
                $join->on('selectors.selectable_id', '=', 'tour_translations.tour_id')
                    ->where('selectors.selectable_type', '=', 'tour')
                    ->where('tour_translations.locale', '=', config('app.locale'))
                    ->orOn('tour_stops.tour_id', '=', 'tour_translations.tour_id')
                    ->where('tour_translations.locale', '=', config('app.locale'));
            })
            ->orderBy('tour_translations.title', $direction);
    }
}
