<?php

namespace App\Models\Transformers;

use A17\Twill\Models\Contracts\TwillModelContract;
use League\Fractal\TransformerAbstract;

class FeaturedTourTransformer extends TransformerAbstract
{
    public function transform(TwillModelContract $tour)
    {
        return [
            (string) $tour->id,
        ];
    }
}
