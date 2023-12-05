<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleMedias;
use A17\Twill\Repositories\Behaviors\HandleRevisions;
use A17\Twill\Repositories\Behaviors\HandleTranslations;
use App\Models\Selector;
use App\Models\Tour;

class TourRepository extends ModuleRepository
{
    use HandleMedias;
    use HandleRevisions;
    use HandleTranslations;

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
        $this->updateBrowser($tour, $fields, 'selector', browserName: 'selectors');
        $this->updateBrowser($tour, $fields, 'stops', browserName: 'tour_stops');
        parent::afterSave($tour, $fields);
    }
}
