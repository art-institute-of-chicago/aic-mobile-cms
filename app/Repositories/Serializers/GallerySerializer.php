<?php

namespace App\Repositories\Serializers;

use League\Fractal\Manager;
use League\Fractal\Resource;
use App\Models\Transformers\GalleryTransformer;

class GallerySerializer
{
    protected ?Manager $manager = null;

    public function __construct()
    {
        $this->manager = new Manager();
        $this->manager->setSerializer(new AssociativeArraySerializer());
    }

    public function serialize($galleries)
    {
        collect($galleries)->each->getAugmentedModel();
        $resource = new Resource\Collection($galleries, new GalleryTransformer(), 'galleries');
        return $this->manager->createData($resource)->toArray();
    }
}
