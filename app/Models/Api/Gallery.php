<?php

namespace App\Models\Api;

use App\Helpers\StringHelpers;
use App\Libraries\Api\Models\BaseApiModel;
use App\Presenters\Admin\GalleryPresenter;

class Gallery extends BaseApiModel
{
    protected array $endpoints = [
        'collection' => '/api/v1/galleries',
        'resource' => '/api/v1/galleries/{id}',
        'search' => '/api/v1/galleries/search',
    ];

    protected $augmented = false;

    protected $presenter = GalleryPresenter::class;
    protected $presenterAdmin = GalleryPresenter::class;

    public function getTypeAttribute()
    {
        return 'gallery';
    }

    public function getTitleSlugAttribute()
    {
        return StringHelpers::getUtf8Slug($this->title);
    }
}
