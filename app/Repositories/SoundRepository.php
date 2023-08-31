<?php

namespace App\Repositories;

use A17\Twill\Models\Contracts\TwillModelContract;
use App\Models\ApiRelation;
use App\Models\Sound;
use App\Models\Selector;
use App\Repositories\Api\BaseApiRepository;

class SoundRepository extends BaseApiRepository
{
    private $selectorNumber;

    public function __construct(Sound $sound)
    {
        $this->model = $sound;
    }

    public function getFormFields(TwillModelContract $sound): array
    {
        $fields = parent::getFormFields($sound);
        $fields['selector_number'] = $sound->selector?->number;
        return $fields;
    }

    public function afterSave(TwillModelContract $sound, array $fields): void
    {
        if (isset($fields['selector_number'])) {
            $selector = Selector::firstOrCreate(['number' => $fields['selector_number']]);
            $apiRelation = ApiRelation::firstOrCreate(['datahub_id' => $sound->datahub_id]);
            if (!$sound->selector || $sound->selector->isNot($selector)) {
                // Detach the audio from the previous selector, if applicable.
                $sound->apiRelation?->morph()->delete();
                // We don't care about `position` for the selector / audio
                // relationship, so give it an arbitrary one.
                $selector->audios()->attach($apiRelation, ['relation' => 'audios', 'position' => 0]);
            }
        } else {
            $sound->apiRelation?->morph()->delete();
        }
        parent::afterSave($sound, $fields);
    }
}
