<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleBrowsers;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\Stop;

class StopRepository extends ModuleRepository
{
    use HandleBrowsers;

    public function __construct(Stop $stop)
    {
        $this->model = $stop;
    }

    public function getFormFields($stop): array
    {
        $fields = parent::getFormFields($stop);
        $fields['browsers']['selectors'] = $this->getFormFieldsForBrowser($stop, 'selector', moduleName: 'selectors');
        $fields['browsers']['tour_stops'] = $this->getFormFieldsForBrowser($stop, 'tours');
        return $fields;
    }

    public function afterSave($stop, array $fields): void
    {
        $this->updateBrowser($stop, $fields, 'selector', browserName: 'selectors');
        $this->updateBrowser($stop, $fields, 'tours', browserName: 'tour_stops');
        parent::afterSave($stop, $fields);
    }
}
