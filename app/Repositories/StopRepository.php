<?php

namespace App\Repositories;

use A17\Twill\Repositories\ModuleRepository;
use App\Models\Stop;

class StopRepository extends ModuleRepository
{
    public function __construct(Stop $model)
    {
        $this->model = $model;
    }
}
