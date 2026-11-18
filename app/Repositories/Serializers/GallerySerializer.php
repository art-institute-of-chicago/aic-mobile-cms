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
        $galleries = $galleries->map(function ($gallery) {
            if ($gallery instanceof ApiGallery) {
                $gallery = $gallery->getAugmentedModel();
            }
            return $gallery;
        });
        $resource = new Resource\Collection($galleries, new GalleryTransformer(), 'galleries');
        return $this->manager->createData($resource)->toArray();
    }
}
