<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Api\CollectionObjectRepository;
use App\Repositories\SelectorRepository;
use App\Repositories\Serializers\ObjectSerializer;
use App\Repositories\StopRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Objects extends Controller
{
    const int SECONDS_UNTIL_STALE = 300; # 5 minutes

    public function __invoke()
    {
        $objects = Cache::flexible(
            self::class,
            [self::SECONDS_UNTIL_STALE, config('cache.max_ttl')],
            $this->getObjects(...)
        );
        $serializer = new ObjectSerializer();
        return $serializer->serialize($objects);
    }

    private function getObjects()
    {
        $publishedStops = App::make(StopRepository::class)
            ->getBaseModel()
            ->newQuery()
            ->visible()
            ->published()
            ->select(DB::raw(1))
            ->whereColumn('stops.id', 'selectors.selectable_id')
            ->where('selectors.selectable_type', 'stop');
        $selectorRepository = App::make(SelectorRepository::class);
        $loanObjects = $selectorRepository
            ->getBaseModel()
            ->newQuery()
            ->visible()
            ->published()
            ->with(['object']) // This can only preload the LoanObjects
            ->whereExists($publishedStops)
            ->get()
            ->map(fn ($selector) => $selector->object)
            ->filter();
        // Since api models cannot be preloaded, bulk retrieve CollectionObjects
        $collectionObjectIds = $selectorRepository
            ->getBaseModel()
            ->newQuery()
            ->visible()
            ->published()
            ->whereExists($publishedStops)
            ->where('object_type', 'collectionObject')
            ->get()
            ->pluck('object_id')
            ->unique()
            ->chunk(100); // The api only allows retrieving 100 records at a time
        $collectionObjectRepository = App::make(CollectionObjectRepository::class);
        $collectionObjects = $collectionObjectIds->reduce(function ($objects, $chunk) use ($collectionObjectRepository) {
            return $objects->concat(
                $collectionObjectRepository
                    ->getBaseModel()
                    ->newQuery()
                    ->with(['gallery'])
                    ->whereIn('id', $chunk->toArray())
                    ->where('is_on_view', true)
                    ->get()
            );
        }, collect());
        return collect()->concat($collectionObjects)->concat($loanObjects);
    }
}
