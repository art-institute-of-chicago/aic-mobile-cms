<?php

namespace App\Models\Api;

use App\Libraries\Api\Models\BaseApiModel;
use App\Helpers\StringHelpers;

class Gallery extends BaseApiModel
{
    protected array $endpoints = [
        'collection' => '/api/v1/galleries',
        'resource' => '/api/v1/galleries/{id}',
        'search' => '/api/v1/galleries/search',
    ];

    protected $augmented = true;
    protected $augmentedModelClass = 'App\Models\Gallery';

    protected $presenter = 'App\Presenters\Admin\GalleryPresenter';
    protected $presenterAdmin = 'App\Presenters\Admin\GalleryPresenter';

    public function getTypeAttribute()
    {
        return 'gallery';
    }

    public function getTitleSlugAttribute()
    {
        return StringHelpers::getUtf8Slug($this->title);
    }
}
