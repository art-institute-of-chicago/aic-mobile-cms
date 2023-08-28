<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleRevisions;
use A17\Twill\Repositories\Behaviors\HandleTranslations;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\Tour;

class TourRepository extends ModuleRepository
{
    use HandleRevisions;
    use HandleTranslations;

    protected $relatedBrowsers = ['stops'];

    public function __construct(Tour $tour)
    {
        $this->model = $tour;
    }

    public function getFormFields($tour): array
    {
        $fields = parent::getFormFields($tour);
        $fields['browsers']['tour_stops'] = $this->getFormFieldsForBrowser($tour, 'stops');
        return $fields;
    }

    public function afterSave($tour, array $fields): void
    {
        $stops = collect($fields['browsers']['tour_stops'])->mapWithKeys(function (array $stop, int $index) {
            return [$stop['id'] => ['position' => $index + 1]];
        });
        $tour->stops()->sync($stops);
        parent::afterSave($tour, $fields);
    }
}
