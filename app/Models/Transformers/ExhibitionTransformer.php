<?php

namespace App\Models\Transformers;

use A17\Twill\Models\Contracts\TwillModelContract;
use League\Fractal\TransformerAbstract;

class ExhibitionTransformer extends TransformerAbstract
{
    public function transform(TwillModelContract $exhibition)
    {
        return [
            'title' => $exhibition->title,
            'image_url' => $exhibition->image_url,
            'exhibition_id' => (string) $exhibition->id,
            'sort' => $exhibition->sort,
        ];
    }
}
