<?php

namespace App\Models\Transformers;

use A17\Twill\Models\Contracts\TwillModelContract;
use App\Repositories\Serializers\OptionalKeyArraySerializer;
use League\Fractal\TransformerAbstract;

class AudioTransformer extends TransformerAbstract
{
    use CustomIncludes;

    public $customIncludes = [
        'translations' => OptionalKeyArraySerializer::class,
    ];

    public function transform(TwillModelContract $audio)
    {
        return [
            $audio->id => $this->withCustomIncludes($audio, [
                'title' => $audio->title,
                'nid' => (string) $audio->id, // Legacy from Drupal
                'audio_file_url' => $audio->content,
                'audio_transcript' => $audio->transcript,
                'track_title' => null, // Legacy from Drupal
            ])
        ];
    }

    public function includeTranslations($audio)
    {
        $translations = $audio->translations;
        return $this->collection($translations, new AudioTranslationTransformer());
    }
}
