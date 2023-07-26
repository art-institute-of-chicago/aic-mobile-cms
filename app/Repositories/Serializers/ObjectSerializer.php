<?php

namespace App\Repositories\Serializers;

use App\Models\Api\Artwork as ApiArtwork;
use App\Models\Artwork;
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
        if ($objects instanceof ApiArtwork) {
            $objects->each->getAugmentedModel();
        } elseif ($objects instanceof Artwork) {
            $objects->each->refreshApi();
        }
        $resource = new Resource\Collection($objects, new ObjectTransformer(), 'objects');
        return $this->manager->createData($resource)->toArray();
    }
}
