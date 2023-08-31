<?php

namespace App\Repositories;

use App\Models\Artwork;
use App\Repositories\Api\BaseApiRepository;

class ArtworkRepository extends BaseApiRepository
{
    public function __construct(Artwork $artwork)
    {
        $this->model = $artwork;
    }

    public function getFormFields($artwork): array
    {
        $fields = parent::getFormFields($artwork);
        $fields['browsers']['gallery'] = $this->getFormFieldsForBrowserApi(
            $artwork,
            relation: 'gallery',
            apiModel: \App\Models\Api\Gallery::class,
            moduleName: 'galleries',
        );
        return $fields;
    }

    public function afterSave($artwork, $fields): void
    {
        // $this->updateBrowserApiRelated($artwork, $fields, 'gallery');
        parent::afterSave($artwork, $fields);
    }
}
