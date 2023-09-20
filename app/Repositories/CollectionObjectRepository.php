<?php

namespace App\Repositories;

use App\Models\CollectionObject;
use App\Repositories\Api\BaseApiRepository;

class CollectionObjectRepository extends BaseApiRepository
{
    protected $apiBrowsers = [
        'gallery'
    ];

    public function __construct(CollectionObject $object)
    {
        $this->model = $object;
    }
}
