<?php

namespace App\Repositories\Serializers;

use App\Models\Transformers\FeaturedTourTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource;

class FeaturedTourSerializer
{
    protected ?Manager $manager = null;

    public function __construct()
    {
        $this->manager = new Manager();
        $this->manager->setSerializer(new FlatArraySerializer());
    }

    public function serialize($tours)
    {
        $tours = collect($tours)->sortBy('position');
        $resource = new Resource\Collection($tours, new FeaturedTourTransformer(), 'featured_tours');
        return $this->manager->createData($resource)->toArray();
    }
}
