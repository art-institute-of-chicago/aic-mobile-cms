<?php

namespace App\Models\Transformers;

use A17\Twill\Models\Contracts\TwillModelContract;
use League\Fractal\TransformerAbstract;

class AudioTranslationTransformer extends TransformerAbstract
{
    public function transform(TwillModelContract $translation)
    {
        return [
            'language' => $translation->locale,
            'title' => $translation->title,
            'track_title' => null, // Legacy frm Drupal
            'audio_fiilename' => null,
            'audio_file_url' => $translation->content,
            'audio_filemime' => null, // Legacy from Drupal
            'audio_filesize' => null, // Legacy from Drupal
            'audio_transcript' => $translation->transcript,
            'credits' => null, // Legacy from Drupal
        ];
    }
}
