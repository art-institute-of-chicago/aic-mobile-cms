<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Serializers\TourSerializer;
use App\Repositories\TourRepository;
use Illuminate\Support\Facades\App;

class Tours extends Controller
{
    public function __invoke()
    {
        $repository = App::make(TourRepository::class);
        $tours = $repository->getBaseModel()->newQuery()->visible()->published()->get();
        $serializer = new TourSerializer();
        return $serializer->serialize($tours);
    }
}
