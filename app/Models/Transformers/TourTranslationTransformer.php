<?php

namespace App\Models\Transformers;

use A17\Twill\Models\Contracts\TwillModelContract;
use League\Fractal\TransformerAbstract;

class TourTranslationTransformer extends TransformerAbstract
{
    public function transform(TwillModelContract $translation)
    {
        return [
            'language' => $translation->locale,
            'title' => $translation->title,
            'description' => $translation->description,
            'description_html' => $translation->description, // Legacy from Drupal
            'intro' => $translation->intro,
            'intro_html' => $translation->intro, // Legacy from Drupal
            'tour_duration' => $translation->duration,
        ];
    }
}
