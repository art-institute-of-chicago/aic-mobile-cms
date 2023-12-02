<?php

namespace App\Repositories\Serializers;

use App\Models\Api\CollectionObject;
use App\Models\Transformers\ObjectTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource;

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
        $objects = $objects->map(function ($object) {
            if ($object instanceof CollectionObject) {
                $object = $object->getAugmentedModel()->refreshApi();
            }
            return $object;
        });
        $resource = new Resource\Collection($objects, new ObjectTransformer(), 'objects');
        return $this->manager->createData($resource)->toArray();
    }
}
