<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Serializers\TourSerializer;
use App\Repositories\TourRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class Tours extends Controller
{
    public function __invoke()
    {
        $repository = App::make(TourRepository::class);
        $tours = Cache::remember('tourRepo-all', 300, function () use ($repository) { return $repository->getBaseModel()->newQuery()->visible()->published()->get(); });
        $serializer = new TourSerializer();
        return $serializer->serialize($tours);
    }
}
