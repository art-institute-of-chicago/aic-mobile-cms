<?php

namespace App\Repositories\Serializers;

use App\Models\Api\Exhibition as ApiExhibition;
use App\Models\Exhibition;
use League\Fractal\Manager;
use League\Fractal\Resource;
use League\Fractal\Serializer\ArraySerializer;
use App\Models\Transformers\ExhibitionTransformer;

class ExhibitionSerializer
{
    protected ?Manager $manager = null;

    public function __construct()
    {
        $this->manager = new Manager();
        $this->manager->setSerializer(new ArraySerializer());
    }

    public function serialize($exhibitions)
    {
        $exhibitions = collect($exhibitions);
        if ($exhibitions instanceof ApiExhibition) {
            $exhibitions->each->getAugmentedModel();
        } elseif ($exhibitions instanceof Exhibition) {
            $exhibitions->each->refreshApi();
        }
        $resource = new Resource\Collection($exhibitions, new ExhibitionTransformer(), 'exhibitions');
        return $this->manager->createData($resource)->toArray();
    }
}
