<?php

namespace App\Repositories\Api;

use App\Models\Api\Audio;

class AudioRepository extends BaseApiRepository
{
    public function __construct(Audio $model)
    {
        $this->model = $model;
    }
}
