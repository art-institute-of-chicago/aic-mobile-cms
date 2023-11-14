<?php

namespace App\Repositories\Serializers;

use League\Fractal\Manager;

/**
 * The general info serializer merges the labels with the list of their various
 * translations.
 */
class GeneralInfoSerializer
{
    protected ?Manager $manager = null;

    public function __construct()
    {
        $this->manager = new Manager();
    }

    public function serialize($labels)
    {
        $labelTranslationSerializer = new LabelTranslationSerializer();
        $labelSerializer = new LabelSerializer();
        return [
            'general_info' => array_merge(
                $labelTranslationSerializer->serialize($labels),
                $labelSerializer->serialize($labels)['labels']->toArray(),
            ),
        ];
    }
}
