<?php

namespace App\Repositories\Serializers;

use App\Models\Transformers\AnnotationTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource;

class AnnotationSerializer
{
    protected ?Manager $manager = null;

    public function __construct()
    {
        $this->manager = new Manager();
        $this->manager->setSerializer(new AssociativeArraySerializer());
    }

    public function serialize($annotations)
    {
        $resource = new Resource\Collection($annotations, new AnnotationTransformer(), 'map_annontations' /* sic */);
        return $this->manager->createData($resource)->toArray();
    }
}
