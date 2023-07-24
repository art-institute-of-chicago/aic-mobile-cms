<?php

namespace App\Libraries\Api\Filters;

use A17\Twill\Services\Listings\Filters\FreeTextSearch;
use Illuminate\Database\Eloquent\Builder;

/**
 * This filter is used in the Twill admin base api controllers to search the API.
 */
class Search extends FreeTextSearch
{
    public function applyFilter(Builder $builder): Builder
    {
        if (!empty($this->searchString) && $this->searchColumns !== []) {
            // \App\Libraries\Api\Models\BaseApiModel with \App\Libraries\Api\Models\Behaviors\HasApiCalls::search()
            return $builder->getModel()->search($this->searchString);
        }

        return $builder;
    }
}
