<?php

namespace App\Models\Transformers;

use A17\Twill\Models\Contracts\TwillModelContract;
use League\Fractal\TransformerAbstract;

class SuggestedObjectTransformer extends TransformerAbstract
{
    public function transform(TwillModelContract $object)
    {
        return [
            (string) $object->id,
        ];
    }
}
