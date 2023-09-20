<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleBrowsers;
use A17\Twill\Repositories\Behaviors\HandleRevisions;
use A17\Twill\Repositories\Behaviors\HandleTranslations;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\Selector;
use App\Models\Stop;
use Illuminate\Support\Str;

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
            $type = $stop->object_type;
            $action = $type === 'collectionObject' ? 'augment' : 'edit';
            return [
                [
                    'id' => $object->id,
                    'name' => $object->title,
                    'edit' => moduleRoute(Str::plural($type), null, $action, $object->id),
                    'endpointType' => $type,
                ]
            ];
        }
        return [];
    }

    protected function updateObjectBrowser($stop, $fields): void
    {
        if (isset($fields['browsers']['objects']) && !empty($fields['browsers']['objects'])) {
            $object = collect($fields['browsers']['objects'])->first();
            $stop->object_id = $object['id'];
            $stop->object_type = $object['endpointType'];
        } else {
            $stop->object_id = null;
            $stop->object_type = null;
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
