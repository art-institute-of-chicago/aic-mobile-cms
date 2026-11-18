<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\SelectorRepository;
use App\Repositories\Serializers\AudioSerializer;
use App\Repositories\StopRepository;
use App\Repositories\TourRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AudioFiles extends Controller
{
    const int SECONDS_UNTIL_STALE = 300; # 5 minutes

    public function __invoke()
    {
        $audios = Cache::flexible(
            self::class,
            [self::SECONDS_UNTIL_STALE, config('cache.max_ttl')],
            $this->getAudios(...)
        );
        $serializer = new AudioSerializer();
        return $serializer->serialize($audios);
    }

    private function getAudios()
    {
        $publishedStops = App::Make(StopRepository::class)
            ->getBaseModel()
            ->newQuery()
            ->visible()
            ->published()
            ->select(DB::raw(1))
            ->whereColumn('stops.id', 'selectors.selectable_id')
            ->where('selectors.selectable_type', 'stop');
        $publishedTours = App::Make(TourRepository::class)
            ->getBaseModel()
            ->newQuery()
            ->visible()
            ->published()
            ->select(DB::raw(1))
            ->whereColumn('tours.id', 'selectors.selectable_id')
            ->where('selectors.selectable_type', 'tour');

        return App::make(SelectorRepository::class)
            ->getBaseModel()
            ->newQuery()
            ->whereExists($publishedStops)
            ->orWhereExists($publishedTours)
            ->get()
            ->map(fn ($selector) => $selector->audios->isEmpty() ? null : $selector->audios)
            ->filter();
    }
}
