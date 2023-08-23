<?php

namespace App\Repositories\Serializers;

use App\Models\Transformers\TourTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource;
use League\Fractal\Serializer\ArraySerializer;

class TourSerializer
{
    protected ?Manager $manager = null;

    public function __construct()
    {
        $this->manager = new Manager();
        $this->manager->setSerializer(new ArraySerializer());
    }

    public function serialize($tours)
    {
        $tours = collect($tours)->sortBy('position');
        $resource = new Resource\Collection($tours, new TourTransformer(), 'tours');
        return $this->manager->createData($resource)->toArray();
    }
}
