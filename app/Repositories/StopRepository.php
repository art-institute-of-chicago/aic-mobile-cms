<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleBrowsers;
use A17\Twill\Repositories\Behaviors\HandleRevisions;
use A17\Twill\Repositories\Behaviors\HandleTranslations;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\Selector;
use App\Models\Stop;

class StopRepository extends ModuleRepository
{
    use HandleBrowsers;
    use HandleRevisions;
    use HandleTranslations;

    public function __construct(Stop $stop)
    {
        $this->model = $stop;
    }

    public function getFormFields($stop): array
    {
        $fields = parent::getFormFields($stop);
        $fields['browsers']['objects'] = $this->getFormFieldsForObject($stop);
        $fields['browsers']['selectors'] = $this->getFormFieldsForBrowser($stop, 'selector', moduleName: 'selectors');
        $fields['browsers']['tour_stops'] = $this->getFormFieldsForBrowser($stop, 'tours');
        return $fields;
    }

    public function afterSave($stop, array $fields): void
    {
        $this->updateObjectBrowser($stop, $fields);
        $this->updateSelectorBrowser($stop, $fields);
        $this->updateBrowser($stop, $fields, 'tours', browserName: 'tour_stops');
        parent::afterSave($stop, $fields);
    }

    protected function getFormFieldsForObject($stop): array
    {
        if ($object = $stop->object) {
            return [
                [
                    'id' => $object->id,
                    'name' => $object->title,
                    'edit' => moduleRoute('artworks', null, 'augment', $object->id),
                    'endpointType' => 'Object',
                ]
            ];
        }
        return [];
    }

    protected function updateObjectBrowser($stop, $fields): void
    {
        if (isset($fields['browsers']['objects'])) {
            $stop->artwork_id = collect($fields['browsers']['objects'])->first()['id'];
        } else {
            $stop->artwork_id = null;
        }
        $stop->save();
    }

    protected function updateSelectorBrowser($stop, $fields): void
    {
        if (isset($fields['browsers']['selectors'])) {
            if ($originalSelector = $stop->selector) {
                $originalSelector->selectable()->dissociate();
                $originalSelector->save();
            };
            if ($selectorData = collect($fields['browsers']['selectors'])->first()) {
                $newSelector = Selector::find($selectorData['id']);
                $newSelector->selectable()->associate($stop);
                $newSelector->save();
            }
        } else {
            $originalSelector = $stop->selector;
            $originalSelector?->selectable()->dissociate();
            $originalSelector?->save();
        }
    }
}
