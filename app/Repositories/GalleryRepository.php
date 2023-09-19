<?php

namespace App\Repositories;

use App\Models\Gallery;
use App\Repositories\Api\BaseApiRepository;

class GalleryRepository extends BaseApiRepository
{
    public function __construct(Gallery $model)
    {
        $this->model = $model;
    }
}
