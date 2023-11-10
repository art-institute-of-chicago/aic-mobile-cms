<?php

namespace App\Models\Transformers;

use A17\Twill\Models\Contracts\TwillModelContract;
use League\Fractal\TransformerAbstract;

class GeneralInfoTransformer extends TransformerAbstract
{
    public function transform(TwillModelContract $label)
    {
        return [
            $label->key => $label->text,
        ];
    }
}
