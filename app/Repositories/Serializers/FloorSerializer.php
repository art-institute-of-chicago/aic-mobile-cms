<?php

namespace App\Repositories\Serializers;

use App\Models\Transformers\FloorTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource;

class FloorSerializer
{
    protected ?Manager $manager = null;

    public function __construct()
    {
        $this->manager = new Manager();
        $this->manager->setSerializer(new AssociativeArraySerializer());
    }

    public function serialize($floors)
    {
        $resource = new Resource\Collection($floors, new FloorTransformer(), 'map_floors');
        return $this->manager->createData($resource)->toArray();
    }
}
