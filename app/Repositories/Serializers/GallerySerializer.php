<?php

namespace App\Repositories\Serializers;

use App\Models\Api\Gallery as ApiGallery;
use App\Models\Gallery;
use App\Models\Transformers\GalleryTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource;

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
        $galleries = collect($galleries);
        if ($galleries instanceof ApiGallery) {
            $galleries->each->getAugmentedModel();
        } elseif ($galleries instanceof Gallery) {
            $galleries->each->refreshApi();
        }
        $resource = new Resource\Collection($galleries, new GalleryTransformer(), 'galleries');
        return $this->manager->createData($resource)->toArray();
    }
}
