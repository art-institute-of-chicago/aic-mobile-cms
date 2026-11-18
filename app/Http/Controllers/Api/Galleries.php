<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\GalleryRepository;
use App\Repositories\Serializers\GallerySerializer;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class Galleries extends Controller
{
    const int SECONDS_UNTIL_STALE = 300; # 5 minutes

    public function __invoke()
    {
        $galleries = Cache::flexible(
            self::class,
            [self::SECONDS_UNTIL_STALE, config('cache.max_ttl')],
            $this->getGalleries(...)
        );
        $serializer = new GallerySerializer();
        return $serializer->serialize($galleries);
    }

    private function getGalleries()
    {
        return App::make(GalleryRepository::class)->getBaseModel()->newQuery()->get();
    }
}
