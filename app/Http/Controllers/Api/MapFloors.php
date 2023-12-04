<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\FloorRepository;
use App\Repositories\Serializers\FloorSerializer;
use Illuminate\Support\Facades\App;

class MapFloors extends Controller
{
    public function __invoke()
    {
        $repository = App::make(FloorRepository::class);
        $floors = $repository->getBaseModel()->newQuery()->get();
        $serializer = new FloorSerializer();
        return $serializer->serialize($floors);
    }
}
