<?php

namespace App\Models\Transformers;

use A17\Twill\Models\Contracts\TwillModelContract;
use League\Fractal\TransformerAbstract;

class SelectorTransformer extends TransformerAbstract
{
    public function transform(TwillModelContract $selector)
    {
        return [
            'object_selector_number' => $selector->number,
            'audio' => $selector->defaultAudio?->id,
        ];
    }
}
