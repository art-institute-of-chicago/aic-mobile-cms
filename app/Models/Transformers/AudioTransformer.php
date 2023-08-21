<?php

namespace App\Models\Transformers;

use A17\Twill\Models\Contracts\TwillModelContract;
use League\Fractal\TransformerAbstract;

class AudioTransformer extends TransformerAbstract
{
    public function transform(TwillModelContract $audio)
    {
        return [
            $audio->id => [
                'title' => $audio->title,
                'nid' => (string) $audio->id, // Legacy from Drupal
                'translations' => [], // TODO implement translations
                'audio_file_url' => $audio->content,
                'audio_transcript' => $audio->transcript,
                'track_title' => null, // Legacy from Drupal
            ]
        ];
    }
}
