<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleRevisions;
use A17\Twill\Repositories\Behaviors\HandleTranslations;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\TourStop;

class TourStopRepository extends ModuleRepository
{
    use HandleRevisions;
    use HandleTranslations;

    public function __construct(TourStop $model)
    {
        $this->model = $model;
    }
}
