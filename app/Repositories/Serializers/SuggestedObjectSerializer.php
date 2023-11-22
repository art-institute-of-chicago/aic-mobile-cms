<?php

namespace App\Repositories\Serializers;

use App\Models\Transformers\SuggestedObjectTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource;

class SuggestedObjectSerializer
{
    protected ?Manager $manager = null;

    public function __construct()
    {
        $this->manager = new Manager();
        $this->manager->setSerializer(new FlatArraySerializer());
    }

    public function serialize($objects)
    {
        $resource = new Resource\Collection($objects, new SuggestedObjectTransformer(), 'search_objects');
        return $this->manager->createData($resource)->toArray();
    }
}
