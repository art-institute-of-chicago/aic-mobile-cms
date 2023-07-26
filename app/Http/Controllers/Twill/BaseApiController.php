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
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Repositories\ModuleRepository;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fieldset;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Boolean;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\Filters\BasicFilter;
use A17\Twill\Services\Listings\Filters\QuickFilter;
use A17\Twill\Services\Listings\TableColumns;
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

    protected function setUpController(): void
    {
        $this->setFeatureField('is_featured');

        $this->disableBulkDelete();
        $this->disableBulkEdit();
        $this->disableBulkPublish();
        $this->disableCreate();
        $this->disableDelete();
        $this->disableEdit();
        $this->disablePermalink();
        $this->disablePublish();
        $this->disableRestore();
    }

    /**
     * Remove Twill table filters.
     */
    public function getIndexTableMainFilters($items, $scopes = []): array
    {
        return [];
    }

    /**
     * Create a new model for augmentation, filling in only the `datahub_id`,
     * then redirect to the edit form.
     */
    public function augment(string $datahubId)
    {
        if ($apiModel = $this->getApiRepository()->getById($datahubId)) {
            $augmentedModel = $this->getRepository()->firstOrCreate(['datahub_id' => $apiModel->id]);
        }
        return $this->redirectToForm($augmentedModel->id);
    }

    public function feature()
    {
        if (($id = $this->request->get('id'))) {
            if ($apiModel = $this->getApiRepository()->getById($id)) {
                $augmentedModel = $this->getRepository()->firstOrCreate(['datahub_id' => $apiModel->id]);
                $this->request->merge(['id' => $augmentedModel->id]);
            }
        }
        return parent::feature();
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
            $this->getApiData($scopes, $forcePagination, $appliedFilters)
        );
    }

    public function getApiData($scopes = [], $forcePagination = false, $appliedFilters = [])
    {
        return $this->getApiRepository()->get(
            with: $this->indexWith,
            scopes: $scopes,
            orders: $this->orderScope(),
            perPage: $this->request->get('offset') ?? $this->perPage,
            forcePagination: $forcePagination,
            appliedFilters: $appliedFilters
        );
    }

    protected function getIndexTableColumns(): TableColumns
    {
        $table = parent::getIndexTableColumns();
        $after = $table->splice(0);
        return $table
            ->push(Boolean::make()
                ->field('is_augmented')
                ->optional()
                ->hide())
            ->push(Text::make()
                ->field('id')
                ->title('Datahub Id')
                ->optional()
                ->hide())
            ->push(Text::make()
                ->field('source_updated_at')
                ->optional()
                ->hide())
            ->push(Text::make()
                ->field('updated_at')
                ->optional()
                ->hide())
            ->merge($after);
    }

    public function getForm(TwillModelContract $model): Form
    {
        $model->refreshApi();
        $form = Form::make()
            ->addFieldset(
                Fieldset::make()
                    ->title('Datahub')
                    ->id('datahub')
                    ->closed()
                    ->fields([
                        Input::make()
                            ->name('datahub_id')
                            ->disabled()
                            ->note('readonly'),
                        Input::make()
                            ->name('source_updated_at')
                            ->disabled()
                            ->note('readonly'),
                    ])
            )
            ->addFieldset(
                Fieldset::make()
                    ->title('Timestamps')
                    ->id('timestamps')
                    ->closed()
                    ->fields([
                        Input::make()
                            ->name('created_at')
                            ->disabled()
                            ->note('readonly'),
                        Input::make()
                            ->name('updated_at')
                            ->disabled()
                            ->note('readonly'),
                    ])
            );
        $content = Form::make()
            ->add(Input::make()
                    ->name('title')
                    ->placeholder($model->getApiModel()->title))
            ->merge($this->additionalFormFields($model, $model->getApiModel()));
        $form->addFieldset(
            Fieldset::make()
                ->title('Content')
                ->id('content')
                ->fields($content->toArray())
        );
        return $form;
    }

    protected function additionalFormFields($model, $apiModel): Form
    {
        return new Form();
    }

    /**
     * Get the applied quick filter.
     */
    protected function applyQuickFilters(array &$requestFilters, array &$appliedFilters): void
    {
        if (array_key_exists('status', $requestFilters)) {
            $filter = $this->quickFilters()->filter(
                fn (QuickFilter $filter) => $filter->getQueryString() === $requestFilters['status']
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
                fn (BasicFilter $filter) => $filter->getQueryString() === $filterKey
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
