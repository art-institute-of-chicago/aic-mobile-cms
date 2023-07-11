<?php

/**
 * WIP.
 *
 * TODO: Refactor this controller so we don't have dependencies to update
 * When we are updating the CMS.
 *
 * Right now the relationship between model and modelApi, redefinition of forms, and it's harcoded nature
 * doesn't scale in a maintenance window.
 *
 */

namespace App\Http\Controllers\Twill;

use A17\Twill\Facades\TwillPermissions;
use A17\Twill\Http\Controllers\Admin\ModuleController;
use A17\Twill\Repositories\ModuleRepository;
use A17\Twill\Services\Listings\Filters\BasicFilter;
use A17\Twill\Services\Listings\Filters\QuickFilter;
use App\Helpers\UrlHelpers;
use App\Libraries\Api\Filters\Search;
use App\Repositories\Api\BaseApiRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class BaseApiController extends ModuleController
{
    /**
     * Option to setup links and the possibility of augmenting a model
     */
    protected $hasAugmentedModel = false;

    protected $localElements = [];

    protected $defaultFilters = [
        'search' => 'search',
    ];

    /**
     * Remove Twill table filters.
     */
    public function getIndexTableMainFilters($items, $scopes = []): array
    {
        return [];
    }

    /**
     * Create a new model to augment it and redirect to the editing form
     */
    public function augment(string $datahubId)
    {
        // Load data from the API
        $apiItem = $this->getApiRepository()->getById($datahubId);

        // Force the datahub_id field
        $data = $apiItem->toArray() + ['datahub_id' => $apiItem->id];

        // Find if we have an existing model before creating an augmented one
        $item = $this->getRepository()->firstOrCreate(['datahub_id' => $apiItem->id], $data);

        // Redirect to edit this model
        return $this->redirectToForm($item->id);
    }

    protected function getRepository(): ModuleRepository
    {
        if ($this->hasAugmentedModel) {
            return parent::getRepository();
        }
        return $this->getApiRepository();
    }

    protected function getApiRepository(): BaseApiRepository
    {
        return $this->app->make("{$this->namespace}\Repositories\\Api\\" . $this->modelName . 'Repository');
    }

    protected function getBrowserTableData(mixed $items, bool $forRepeater = false): array
    {
        // Ensure data is an array and not an object to avoid json_encode wrong conversion
        $results = array_values(parent::getBrowserTableData($items));

        // WEB-1187: Fix the edit link
        $results = array_map(function ($result) {
            if (UrlHelpers::moduleRouteExists($this->moduleName, $this->routePrefix, 'augment')) {
                $result['edit'] = moduleRoute($this->moduleName, $this->routePrefix, 'augment', [$result['id']]);
            }

            return $result;
        }, $results);

        return $results;
    }

    public function getIndexItems(array $scopes = [], bool $forcePagination = false): Collection|LengthAwarePaginator
    {
        if (TwillPermissions::enabled() && TwillPermissions::getPermissionModule($this->moduleName)) {
            $scopes += ['accessible' => true];
        }

        $requestFilters = $this->getRequestFilters();
        $appliedFilters = [];
        $this->applyQuickFilters($requestFilters, $appliedFilters);
        $this->applyBasicFilters($requestFilters, $appliedFilters);
        return $this->transformIndexItems(
            $this->getApiRepository()->get(
                with: $this->indexWith,
                scopes: $scopes,
                orders: $this->orderScope(),
                perPage: $this->request->get('offset') ?? $this->perPage,
                forcePagination: $forcePagination,
                appliedFilters: $appliedFilters
            )
        );
    }

    /**
     * Get the applied quick filter.
     */
    protected function applyQuickFilters(array &$requestFilters, array &$appliedFilters): void
    {
        if (array_key_exists('status', $requestFilters)) {
            $filter = $this->quickFilters()->filter(
                fn(QuickFilter $filter) => $filter->getQueryString() === $requestFilters['status']
            )->first();

            if ($filter !== null) {
                $appliedFilters[] = $filter;
            }
        }
        unset($requestFilters['status']);
    }

    /**
     * Get other filters that need to applied. Use the API search filter when
     * requested.
     */
    protected function applyBasicFilters(array &$requestFilters, array &$appliedFilters): void
    {
        foreach ($requestFilters as $filterKey => $filterValue) {
            $filter = $this->filters()->filter(
                fn(BasicFilter $filter) => $filter->getQueryString() === $filterKey
            )->first();

            if ($filter !== null) {
                $appliedFilters[] = $filter->withFilterValue($filterValue);
            } elseif ($filterKey === 'search') {
                $appliedFilters[] = Search::make()
                    ->searchFor($filterValue)
                    ->searchColumns($this->searchColumns);
            }
        }
    }

    protected function transformIndexItems(Collection|LengthAwarePaginator $items): Collection|LengthAwarePaginator
    {
        if ($this->hasAugmentedModel) {
            $ids = $items->pluck('id')->toArray();
            $this->localElements = $this->repository->whereIn('datahub_id', $ids)->get();
            $items->setCollection($items->getCollection()->map(function ($item) {
                if ($element = collect($this->localElements)->where('datahub_id', $item->id)->first()) {
                    $item->setAugmentedModel($element);
                }
                return $item;
            }));
        }
        return $items;
    }

    /**
     * Disable sorting by default for API listings. This has to be implemented individually on each controller
     */
    protected function orderScope(): array
    {
        return [];
    }

    protected function indexItemData($item)
    {
        if ($this->hasAugmentedModel) {
            if ($localItem = collect($this->localElements)->where('datahub_id', $item->id)->first()) {
                $editRoute = moduleRoute($this->moduleName, $this->routePrefix, 'edit', [$localItem->id]);
            } else {
                $editRoute = moduleRoute($this->moduleName, $this->routePrefix, 'augment', [$item->id]);
            }
        } else {
            $editRoute = null;
        }

        return ['edit' => $editRoute];
    }
}
