<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleFiles;
use A17\Twill\Repositories\Behaviors\HandleTranslations;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\Floor;

class FloorRepository extends ModuleRepository
{
    use HandleFiles;
    use HandleTranslations;

    public function __construct(Floor $floor)
    {
        $this->model = $floor;
    }
}
