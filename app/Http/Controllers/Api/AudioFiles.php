<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\SelectorRepository;
use App\Repositories\Serializers\AudioSerializer;
use App\Repositories\StopRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class AudioFiles extends Controller
{
    public function __invoke()
    {
        $publishedStops = App::Make(StopRepository::class)
            ->getBaseModel()
            ->newQuery()
            ->visible()
            ->published()
            ->select(DB::raw(1))
            ->whereColumn('stops.id', 'selectors.selectable_id')
            ->where('selectors.selectable_type', 'stop');
        $selectorRepository = App::make(SelectorRepository::class);
        $audios = $selectorRepository
            ->getBaseModel()
            ->newQuery()
            ->whereExists($publishedStops)
            ->get()
            ->map(fn ($selector) => $selector->audios->isEmpty() ? null : $selector->audios)
            ->filter();
        $serializer = new AudioSerializer();
        return $serializer->serialize($audios);
    }
}
