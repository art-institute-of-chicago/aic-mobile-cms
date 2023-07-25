<?php

namespace App\Repositories\Serializers;

use League\Fractal\Manager;
use League\Fractal\Resource;
use App\Models\Transformers\ObjectTransformer;

class ObjectSerializer
{
    protected ?Manager $manager = null;

    public function __construct()
    {
        $this->manager = new Manager();
        $this->manager->setSerializer(new AssociativeArraySerializer());
    }

    public function serialize($objects)
    {
        $resource = new Resource\Collection($objects, new ObjectTransformer(), 'objects');
        return $this->manager->createData($resource)->toArray();
    }
}
