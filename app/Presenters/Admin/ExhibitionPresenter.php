<?php

namespace App\Presenters\Admin;

use App\Presenters\BasePresenter;

class ExhibitionPresenter extends BasePresenter
{
    public function augmented()
    {
        return $this->entity->getAugmentedModel() ? 'Yes' : 'No';
    }

    protected function collectionFilteredUrl()
    {
        return route('collection', [
            'exhibition_ids' => $this->entity->id,
        ]);
    }
}
