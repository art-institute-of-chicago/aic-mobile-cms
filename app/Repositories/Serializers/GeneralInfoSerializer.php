<?php

namespace App\Repositories\Serializers;

use App\Models\Transformers\GeneralInfoTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource;
use League\Fractal\Serializer\ArraySerializer;

class GeneralInfoSerializer
{
    protected ?Manager $manager = null;

    public function __construct()
    {
        $this->manager = new Manager();
        $this->manager->setSerializer(new AssociativeArraySerializer());
    }

    public function serialize($labels)
    {
        $resource = new Resource\Collection($labels, new GeneralInfoTransformer(), 'general_info');
        return $this->manager->createData($resource)->toArray();
    }
}
