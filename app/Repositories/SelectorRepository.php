<?php

namespace App\Repositories;

use App\Models\Audio;
use App\Models\CollectionObject;
use App\Models\Selector;
use App\Repositories\ModuleRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class SelectorRepository extends ModuleRepository
{
    protected $apiBrowsers = [
        'apiAudios' => [
            'moduleName' => 'audio',
            'isApiRelation' => true,
        ]
    ];

    public function __construct(Selector $selector)
    {
        $this->model = $selector;
    }

    public function order(Builder $query, array $orderBy = []): Builder
    {
        // Default sort by number instead of creation date.
        $orderBy['number'] ??= 'asc';
        unset($orderBy['created_at']);

        if (array_key_exists('tour_title', $orderBy)) {
            $query->orderByTourTitle($orderBy['tour_title']);
            unset($orderBy['tour_title']);
        }
        return parent::order($query, $orderBy);
    }

    public function getFormFields($selector): array
    {
        $fields = parent::getFormFields($selector);
        $fields['browsers']['objects'] = $this->getFormFieldsForObject($selector);
        $fields['browsers']['selectables'] = $this->getFormFieldsForSelectable($selector);
        return $fields;
    }

    public function afterSave($selector, array $fields): void
    {
        $this->updateObjectBrowser($selector, $fields);
        $this->updateSelectableBrowser($selector, $fields);
        $this->updateAudioBrowser($selector, $fields);
        parent::afterSave($selector, $fields);
    }

    protected function getFormFieldsForObject($selector): array
    {
        if ($object = $selector->object) {
            $type = $selector->object_type;
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

    protected function getFormFieldsForSelectable($selector): array
    {
        if ($selectable = $selector->selectable) {
            return [
                [
                    'id' => $selectable->id,
                    'name' => $selectable->title,
                    'edit' => moduleRoute(Str::plural(class_basename($selectable)), null, 'edit', $selectable->id),
                    'endpointType' => Str::lower(class_basename($selectable)),
                ]
            ];
        }
        return [];
    }

    protected function updateObjectBrowser($selector, $fields): void
    {
        if (isset($fields['browsers']['objects']) && !empty($fields['browsers']['objects'])) {
            $object = collect($fields['browsers']['objects'])->first();
            $selector->fill([
                'object_id' => $object['id'],
                'object_type' => $object['endpointType'],
            ]);
            if ($object['endpointType'] == 'collectionObject') {
                CollectionObject::firstOrCreate(['datahub_id' => $object['id']]);
            }
        } else {
            $selector->fill([
                'object_id' => null,
                'object_type' => null,
            ]);
        }
        $selector->save();
    }

    protected function updateSelectableBrowser($selector, $fields): void
    {
        if (isset($fields['browsers']['selectables'])) {
            if ($selectableData = collect($fields['browsers']['selectables'])->first()) {
                $classBasename = Str::ucfirst($selectableData['endpointType']);
                $selectable = "\\App\\Models\\$classBasename"::find($selectableData['id']);
                $selector->selectable()->associate($selectable);
            }
        } else {
            $selector->selectable()->dissociate();
        }
        $selector->save();
    }

    protected function updateAudioBrowser($selector, $fields)
    {
        if (isset($fields['browsers']['apiAudios'])) {
            foreach ($fields['browsers']['apiAudios'] as $audio) {
                $audio = Audio::firstOrCreate([
                    'datahub_id' => $audio['id'],
                    'selector_id' => $selector->id,
                ]);
                $audio->save();
            }
        }
    }
}
