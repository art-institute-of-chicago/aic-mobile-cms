<?php

namespace App\Models\Transformers;

use A17\Twill\Models\Contracts\TwillModelContract;
use League\Fractal\TransformerAbstract;

class StopTransformer extends TransformerAbstract
{
    public function transform(TwillModelContract $stop): array
    {
        return [
            'object' => class_basename($stop->selector?->object::class) . ':' . $stop->object_id,
            'audio_id' => (string) $stop->selector?->number,
            'audio_bumper' => null, // Legacy from Drupal
            'sort' => $stop->pivot->position,
        ];
    }
}
