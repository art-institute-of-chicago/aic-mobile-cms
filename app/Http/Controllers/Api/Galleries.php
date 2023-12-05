<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\GalleryRepository;
use App\Repositories\Serializers\GallerySerializer;
use Illuminate\Support\Facades\App;

class Galleries extends Controller
{
    public function __invoke()
    {
        $repository = App::make(GalleryRepository::class);
        $galleries = $repository->getBaseModel()->newQuery()->get();
        $serializer = new GallerySerializer();
        return $serializer->serialize($galleries);
    }
}
