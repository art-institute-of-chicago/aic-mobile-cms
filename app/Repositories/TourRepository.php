<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleRevisions;
use A17\Twill\Repositories\Behaviors\HandleTranslations;
use App\Models\Selector;
use App\Models\Tour;

class TourRepository extends ModuleRepository
{
    use HandleRevisions;
    use HandleTranslations;

    protected $relatedBrowsers = ['stops'];
    protected $apiBrowsers = [
        'gallery'
    ];

    public function __construct(Tour $tour)
    {
        $this->model = $tour;
    }

    public function getFormFields($tour): array
    {
        $fields = parent::getFormFields($tour);
        $fields['browsers']['selectors'] = $this->getFormFieldsForBrowser($tour, 'selector', moduleName: 'selectors');
        $fields['browsers']['tour_stops'] = $this->getFormFieldsForBrowser($tour, 'stops');
        return $fields;
    }

    public function afterSave($tour, array $fields): void
    {
        $this->updateSelectorBrowser($tour, $fields);
        $this->updateTourStopsBrowser($tour, $fields);
        parent::afterSave($tour, $fields);
    }

    protected function updateSelectorBrowser($tour, $fields): void
    {
        if (isset($fields['browsers']['selectors'])) {
            if ($originalSelector = $tour->selector) {
                $originalSelector->selectable()->dissociate();
                $originalSelector->save();
            };
            if ($selectorData = collect($fields['browsers']['selectors'])->first()) {
                $newSelector = Selector::find($selectorData['id']);
                $newSelector->selectable()->associate($tour);
                $newSelector->save();
            }
        } else {
            $originalSelector = $tour->selector;
            $originalSelector?->selectable()->dissociate();
            $originalSelector?->save();
        }
    }

    protected function updateTourStopsBrowser($tour, $fields): void
    {
        $stops = collect($fields['browsers']['tour_stops'])->mapWithKeys(function (array $stop, int $index) {
            return [$stop['id'] => ['position' => $index + 1]];
        });
        $tour->stops()->sync($stops);
    }
}
