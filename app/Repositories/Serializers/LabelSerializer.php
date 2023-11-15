<?php

namespace App\Repositories\Serializers;

use App\Models\Transformers\LabelTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource;

class LabelSerializer
{
    protected string $locale;

    protected ?Manager $manager = null;

    public function __construct(?string $locale = null)
    {
        $this->locale = is_null($locale) ? config('app.locale') : $locale;
        $this->manager = new Manager();
        $this->manager->setSerializer(new AssociativeArraySerializer());
    }

    public function serialize($labels)
    {
        $resource = new Resource\Collection($labels, new LabelTransformer($this->locale), 'labels');
        return $this->manager->createData($resource)->toArray();
    }
}
