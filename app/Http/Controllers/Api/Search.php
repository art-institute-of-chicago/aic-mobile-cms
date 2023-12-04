<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Api\CollectionObjectRepository;
use App\Repositories\Serializers\SearchSerializer;
use Illuminate\Support\Facades\App;

class Search extends Controller
{
    public function __invoke()
    {
        $repository = App::make(CollectionObjectRepository::class);
        $suggestedObjects = $repository->getBaseModel()->newQuery()->mostViewed()->get();
        $serializer = new SearchSerializer();
        return $serializer->serialize($suggestedObjects);
    }
}
