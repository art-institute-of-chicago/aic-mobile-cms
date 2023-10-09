<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleMedias;
use App\Models\CollectionObject;
use App\Repositories\Api\BaseApiRepository;

class CollectionObjectRepository extends BaseApiRepository
{
    use HandleMedias;

    protected $apiBrowsers = [
        'gallery'
    ];

    public function __construct(CollectionObject $object)
    {
        $this->model = $object;
    }
}
