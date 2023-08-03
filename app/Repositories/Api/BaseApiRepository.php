<?php

namespace App\Repositories\Api;

use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Models\Model;
use App\Repositories\Behaviors\HandleApiRelations;
use App\Repositories\ModuleRepository;
use Illuminate\Database\Eloquent\Builder;

abstract class BaseApiRepository extends ModuleRepository
{
    use HandleApiRelations;

    public function getById(int $id, array $with = [], array $withCount = []): TwillModelContract
    {
        $item = $this->model->with($with)->withCount($withCount)->findOrFail($id);

        if ($item instanceof Model) {
            return $item->refreshApi();
        }

        return $item;
    }

    public function filter($query, array $scopes = []): Builder
    {
        // Perform a search first and then filter.
        // Because endpoints are different is preferable to acknoledge a search before
        // computing the rest of the filters
        $this->searchIn($query, $scopes, 'search', []);

        return parent::filter($query, $scopes);
    }

    public function searchIn($query, &$scopes, $scopeField, $orFields = [])
    {
        if (isset($scopes[$scopeField]) && is_string($scopes[$scopeField])) {
            $query->search($scopes[$scopeField]);
            unset($scopes[$scopeField]);
        }
    }

    public function forSearchQuery($string, $perPage = null, $columns = [], $pageName = 'page', $page = null, $options = [])
    {
        // Build the search query
        $search = $this->model->search($string);

        // Perform the query
        $results = $search->getSearch($perPage, $columns, $pageName, $page, $options);

        return $results;
    }

    /**
     * The following function was adapted from
     * A17\Twill\Repositories\ModuleRepository v2.5.3 for backwards
     * compatibility.
     */
    public function getCountByStatusSlug(string $slug, array $scope = []): int
    {
        $dbQuery = $this->model->newQuery();
        $apiQuery = $this->model->getApiModel()->newQuery();
        switch ($slug) {
            case 'all':
                return $dbQuery->count() + $apiQuery->count();
            case 'published':
                return $dbQuery->published()->count();
            case 'draft':
                return $dbQuery->draft()->count();
            case 'trash':
                return $dbQuery->onlyTrashed()->count();
        }

        foreach ($this->traitsMethods(__FUNCTION__) as $method) {
            if (($count = $this->$method($slug)) !== false) {
                return $count;
            }
        }

        return 0;
    }
}
