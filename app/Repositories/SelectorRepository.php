<?php

namespace App\Repositories;

use A17\Twill\Repositories\ModuleRepository;
use App\Models\Selector;
use App\Models\Api\Sound;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class SelectorRepository extends ModuleRepository
{
    use Behaviors\HandleApiBrowsers;

    public function __construct(Selector $selector)
    {
        $this->model = $selector;
    }

    public function order(Builder $query, array $orderBy = []): Builder
    {
        // Default sort by number instead of creation date.
        $orderBy['number'] ??= 'asc';
        unset($orderBy['created_at']);

        if (array_key_exists('selectable_title', $orderBy)) {
            $query->orderBySelectableTitle($orderBy['selectable_title']);
            unset($orderBy['selectable_title']);
        }
        if (array_key_exists('tour_title', $orderBy)) {
            $query->orderByTourTitle($orderBy['tour_title']);
            unset($orderBy['tour_title']);
        }
        return parent::order($query, $orderBy);
    }

    public function getFormFields($selector): array
    {
        $fields = parent::getFormFields($selector);
        $fields['browsers']['audios'] = $this->getFormFieldsForBrowserApi(
            $selector,
            relation: 'audios',
            apiModel: Sound::class,
            moduleName: 'sounds',
        );
        $fields['browsers']['selectables'] = $this->getFormFieldsForSelectable($selector);
        return $fields;
    }

    public function afterSave($selector, array $fields): void
    {
        $this->updateBrowserApiRelated($selector, $fields, 'audios');
        $this->updateSelectableBrowser($selector, $fields);
        parent::afterSave($selector, $fields);
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
}
