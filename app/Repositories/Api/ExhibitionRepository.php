<?php

namespace App\Repositories\Api;

use App\Models\Api\Exhibition;

class ExhibitionRepository extends BaseApiRepository
{
    public function __construct(Exhibition $model)
    {
        $this->model = $model;
    }
}
