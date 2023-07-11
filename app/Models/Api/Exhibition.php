<?php

namespace App\Models\Api;

use App\Helpers\StringHelpers;
use App\Libraries\Api\Models\BaseApiModel;
use App\Presenters\Admin\ExhibitionPresenter;

class Exhibition extends BaseApiModel
{
    protected array $endpoints = [
        'collection' => '/api/v1/exhibitions',
        'resource' => '/api/v1/exhibitions/{id}',
        'search' => '/api/v1/exhibitions/search',
    ];

    protected $augmented = true;
    protected $augmentedModelClass = \App\Models\Exhibition::class;

    protected $presenter = ExhibitionPresenter::class;
    protected $presenterAdmin = ExhibitionPresenter::class;

    public function getTypeAttribute()
    {
        return 'exhibition';
    }

    public function getTitleSlugAttribute()
    {
        return StringHelpers::getUtf8Slug($this->title);
    }
}
