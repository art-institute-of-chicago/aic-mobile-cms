<?php

namespace App\Models\Transformers;

use A17\Twill\Models\Contracts\TwillModelContract;
use League\Fractal\TransformerAbstract;

class LabelTransformer extends TransformerAbstract
{
    public string $locale;

    public function __construct(string $locale)
    {
        $this->locale = $locale;
    }
    public function transform(TwillModelContract $label)
    {
        return [
            $label->key => $label->translate($this->locale)?->text,
        ];
    }
}
