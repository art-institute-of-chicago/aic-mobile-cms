<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleFiles;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\MapFloor;

class MapFloorRepository extends ModuleRepository
{
    use HandleFiles;

    public function __construct(MapFloor $mapFloor)
    {
        $this->model = $mapFloor;
    }
}
