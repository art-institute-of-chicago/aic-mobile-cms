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
        $audios = collect($audios);
        if ($audios instanceof ApiAudio) {
            $audios->each->getAugmentedModel();
        } elseif ($audios instanceof Audio) {
            $audios->each->refreshApi();
        }
        $resource = new Resource\Collection($audios, new AudioTransformer(), 'audio_files');
        return $this->manager->createData($resource)->toArray();
    }
}
