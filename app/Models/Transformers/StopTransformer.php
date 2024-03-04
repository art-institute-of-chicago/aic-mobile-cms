<?php

namespace App\Models\Transformers;

use A17\Twill\Models\Contracts\TwillModelContract;
use League\Fractal\TransformerAbstract;
use App\Helpers\Util;
use App\Models\CollectionObject;
use App\Models\LoanObject;

class StopTransformer extends TransformerAbstract
{
    public function transform(TwillModelContract $stop): array
    {
        switch (get_class($stop->selector?->object)) {
            case CollectionObject::class:
                $objectType = Util::COLLECTION_OBJECT;
                break;
            case LoanObject::class:
                $objectType = Util::LOAN_OBJECT;
                break;
            default:
                $objectType = 0;
        }
        $nid = Util::cantorPair($objectType, $stop->object_id);
        return [
            'object' => $nid,
            'audio_id' => (string) $stop->selector?->audios->first()?->id,
            'audio_bumper' => null, // Legacy from Drupal
            'sort' => $stop->pivot->position,
        ];
    }
}
