<?php

namespace App\Repositories\Serializers;

use App\Models\Api\Audio as ApiAudio;
use App\Models\Audio;
use App\Models\Transformers\AudioTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource;

class AudioSerializer
{
    protected ?Manager $manager = null;

    public function __construct()
    {
        $this->manager = new Manager();
        $this->manager->setSerializer(new AssociativeArraySerializer());
    }

    public function serialize($audios)
    {
        $audios = $audios->map(function ($translations) {
            $translations = $translations->map(function ($audio) {
                if ($audio instanceof ApiAudio) {
                    $audio = $audio->getAugmentedModel();
                }
                return $audio->refreshApi();
            });
            $defaultTranslation = $translations->firstWhere('locale', config('app.locale'));
            $defaultTranslation->translations = $translations->whereNotIn('locale', [config('app.locale')]);
            return $defaultTranslation;
        });
        $resource = new Resource\Collection($audios, new AudioTransformer(), 'audio_files');
        return $this->manager->createData($resource)->toArray();
    }
}
