<?php

namespace App\Models\Transformers;

use A17\Twill\Models\Contracts\TwillModelContract;
use League\Fractal\TransformerAbstract;

class StopTransformer extends TransformerAbstract
{
    use HasNid;

    public function transform(TwillModelContract $stop): array
    {
        return [
            'object' => $this->calculateNid($stop->selector?->object),
            'audio_id' => (string) $stop->selector?->defaultAudio?->id,
            'audio_bumper' => null, // Legacy from Drupal
            'sort' => $stop->pivot->position,
        ];
    }
}
