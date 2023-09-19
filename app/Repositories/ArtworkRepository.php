<?php

namespace App\Repositories;

use App\Models\Artwork;
use App\Repositories\Api\BaseApiRepository;

class ArtworkRepository extends BaseApiRepository
{
    protected $apiBrowsers = [
        'gallery'
    ];

    public function __construct(Artwork $artwork)
    {
        $this->model = $artwork;
    }
}
