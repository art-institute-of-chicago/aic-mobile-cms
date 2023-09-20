<?php

namespace App\Repositories\Api;

use App\Models\Api\CollectionObject;

class CollectionObjectRepository extends BaseApiRepository
{
    public function __construct(CollectionObject $model)
    {
        $this->model = $model;
    }
}
