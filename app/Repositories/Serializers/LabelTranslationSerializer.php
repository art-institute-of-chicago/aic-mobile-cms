<?php

namespace App\Repositories\Serializers;

use App\Models\Transformers\LabelTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource;

class LabelTranslationSerializer
{
    protected ?Manager $manager = null;

    public function __construct()
    {
        $this->manager = new Manager();
        $this->manager->setSerializer(new AssociativeArraySerializer());
    }

    public function serialize($labels)
    {
        $translations = [];
        $locales = collect(getLocales())->reject(fn($locale) => $locale == config('app.locale'));
        foreach ($locales as $locale) {
            $resource = new Resource\Collection($labels, new LabelTransformer($locale), 'labels');
            $translations[] = array_merge(
                ['language' => $locale],
                $this->manager->createData($resource)->toArray()['labels']->toArray(),
            );
        }
        return ['translations' => $translations];
    }
}
