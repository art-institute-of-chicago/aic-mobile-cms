<?php

namespace App\Repositories;

use App\Models\Sound;
use App\Repositories\Api\BaseApiRepository;

class SoundRepository extends BaseApiRepository
{
    public function __construct(Sound $model)
    {
        $this->model = $model;
    }
}
