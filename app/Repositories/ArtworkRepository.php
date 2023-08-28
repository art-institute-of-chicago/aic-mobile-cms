<?php

namespace App\Repositories;

use App\Models\Artwork;
use App\Repositories\Api\BaseApiRepository;

class ArtworkRepository extends BaseApiRepository
{
    public function __construct(Artwork $model)
    {
        $this->model = $model;
    }

    public function afterSave($object, $fields): void
    {
        $this->updateMultiBrowserApiRelated($object, $fields, 'related_items', ['galleries' => true]);

        parent::afterSave($object, $fields);
    }
}
