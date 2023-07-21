<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleBlocks;
use A17\Twill\Repositories\Behaviors\HandleMedias;
use App\Models\Artwork;
use App\Repositories\Api\BaseApiRepository;

class ArtworkRepository extends BaseApiRepository
{
    use HandleMedias;

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
