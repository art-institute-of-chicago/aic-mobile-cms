<?php

namespace App\Repositories\Serializers;

use App\Models\Api\CollectionObject as ApiCollectionObject;
use App\Models\CollectionObject;
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
        $objects = collect($objects);
        if ($objects instanceof ApiCollectionObject) {
            $objects->each->getAugmentedModel();
        } elseif ($objects instanceof CollectionObject) {
            $objects->each->refreshApi();
        }
        $resource = new Resource\Collection($objects, new ObjectTransformer(), 'objects');
        return $this->manager->createData($resource)->toArray();
    }
}
