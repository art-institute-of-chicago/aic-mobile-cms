<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleMedias;
use A17\Twill\Repositories\Behaviors\HandleRevisions;
use A17\Twill\Repositories\Behaviors\HandleTranslations;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\Tour;

class TourRepository extends ModuleRepository
{
    use HandleMedias;
    use HandleRevisions;
    use HandleTranslations;

    public function __construct(Tour $model)
    {
        $this->model = $model;
    }
}
