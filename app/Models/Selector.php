<?php

namespace App\Models;

use A17\Twill\Models\Model;
use App\Models\Behaviors\HasApiRelations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class Selector extends Model
{
    use HasApiRelations;
    use HasFactory;

    protected $fillable = [
        'notes',
        'number',
    ];

    protected $appends = [
        'locales',
        'selectable_title',
        'title',
        'tour_title',
    ];

    public function apiRelatables(): MorphMany
    {
        return $this->morphMany(ApiRelatable::class, 'api_relatable');
    }

    public function audio(): MorphToMany
    {
        return $this->apiElements()->where('relation', 'audio');
    }

    public function selectable(): MorphTo
    {
        return $this->morphTo();
    }

    public function locales(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->apiModels('audio', 'Audio')->pluck('locale')->join(', '),
        );
    }

    public function title(): Attribute
    {
        return Attribute::make(
            get: fn (): string => (string) $this->number,
        );
    }

    public function selectableTitle(): Attribute
    {
        $selectable = $this->selectable;
        return Attribute::make(
            get: function () use ($selectable): string {
                $title = $selectable?->title;
                if ($selectable instanceof Tour) {
                    $title .= ' Intro';
                }
                return (string) $title;
            }
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

    public function scopeOrderBySelectableTitle(Builder $query, string $direction = 'asc'): Builder
    {
        return $query
            ->select(
                'selectors.*',
                DB::raw('COALESCE(stop_translations.title, tour_translations.title) as selectable_title'),
            )
            ->leftJoin('stop_translations', function (JoinClause $join) {
                $join->on('selectors.selectable_id', '=', 'stop_translations.stop_id')
                    ->where('selectors.selectable_type', '=', 'stop')
                    ->where('stop_translations.locale', '=', config('app.locale'));
            })
            ->leftJoin('tour_translations', function (JoinClause $join) {
                $join->on('selectors.selectable_id', '=', 'tour_translations.tour_id')
                    ->where('selectors.selectable_type', '=', 'tour')
                    ->where('tour_translations.locale', '=', config('app.locale'));
            })
            ->orderBy('selectable_title', $direction);
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
