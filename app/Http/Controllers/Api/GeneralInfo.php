<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\LabelRepository;
use App\Repositories\Serializers\GeneralInfoSerializer;
use Illuminate\Support\Facades\App;

class GeneralInfo extends Controller
{
    public function __invoke()
    {
        $repository = App::make(LabelRepository::class);
        $labels = $repository->getBaseModel()->newQuery()->get();
        $serializer = new GeneralInfoSerializer();
        return $serializer->serialize($labels);
    }
}
