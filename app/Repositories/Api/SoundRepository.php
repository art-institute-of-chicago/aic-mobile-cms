<?php

namespace App\Repositories\Api;

use App\Models\Api\Sound;

class SoundRepository extends BaseApiRepository
{
    public function __construct(Sound $model)
    {
        $this->model = $model;
    }
}
