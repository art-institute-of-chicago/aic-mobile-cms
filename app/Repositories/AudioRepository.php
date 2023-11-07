<?php

namespace App\Repositories;

use A17\Twill\Models\Contracts\TwillModelContract;
use App\Models\ApiRelation;
use App\Models\Audio;
use App\Models\Selector;
use App\Repositories\Api\BaseApiRepository;

class AudioRepository extends BaseApiRepository
{
    public function __construct(Audio $audio)
    {
        $this->model = $audio;
    }

    public function getFormFields(TwillModelContract $audio): array
    {
        $fields = parent::getFormFields($audio);
        $fields['selector_number'] = $audio->selector?->number;
        return $fields;
    }

    public function afterSave(TwillModelContract $audio, array $fields): void
    {
        if (isset($fields['title_markup'])) {
            $audio->title = $fields['title_markup'];
            $audio->save();
        }
        if (isset($fields['selector_number'])) {
            $selector = Selector::firstOrCreate(['number' => $fields['selector_number']]);
            $apiRelation = ApiRelation::firstOrCreate(['datahub_id' => $audio->datahub_id]);
            if (!$audio->selector || $audio->selector->isNot($selector)) {
                // Detach the audio from the previous selector, if applicable.
                $audio->apiRelation?->morph()->delete();
                // We don't care about `position` for the selector / audio
                // relationship, so give it an arbitrary one.
                $selector->audio()->attach($apiRelation, ['relation' => 'audio', 'position' => 0]);
            }
        }
        parent::afterSave($audio, $fields);
    }
}
