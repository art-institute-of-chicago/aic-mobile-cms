<?php

namespace App\Repositories\Behaviors;

use A17\Twill\Models\RelatedItem;
use App\Models\ApiRelation;
use App\Libraries\Api\Models\BaseApiModel;
use App\Helpers\UrlHelpers;
use DamsImageService;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Mimic Twill's generalized behavior for API browsers
 *
 * @see A17\Twill\Repositories\Behaviors\HandleBrowsers
 */
trait HandleApiBrowsers
{
    /**
     * All API browsers used in the model, as an array of browser names:
     * [
     *     'books',
     *     'publications'
     * ].
     *
     * When only the browser name is given, the rest of the parameters are inferred from the name.
     * The parameters can also be overridden with an array:
     * [
     *     'books',
     *     'publication' => [
     *         'routePrefix' => 'collections',
     *         'titleKey' => 'name'
     *     ]
     * ]
     *
     * @var array
     */
    protected $apiBrowsers = [];

    /**
     * @param \App\Libraries\Api\Models\BaseApiModel $object
     * @param array $fields
     * @return void
     */
    public function afterSaveHandleApiBrowsers($object, $fields)
    {
        foreach ($this->getApiBrowsers() as $browser) {
            if ($browser['isApiRelation']) {
                $this->updateBrowserApiRelated(
                    $object,
                    $fields,
                    $browser['relation'],
                    $browser['positionAttribute']
                );
            } else {
                $this->updateApiBrowser(
                    $object,
                    $fields,
                    $browser['relation'],
                    $browser['positionAttribute'],
                    $browser['browserName']
                );
            }
        }
    }

    /**
     * @param \App\Libraries\Api\Models\BaseApiModel $object
     * @param array $fields
     * @return array
     */
    public function getFormFieldsHandleApiBrowsers($object, $fields)
    {
        foreach ($this->getApiBrowsers() as $browser) {
            $relation = $browser['relation'];
            if (collect($object->$relation)->isNotEmpty()) {
                $fields['browsers'][$browser['browserName']] = $this->getFormFieldsForApiBrowser(
                    $object,
                    $relation,
                    $browser['model'],
                    $browser['routePrefix'],
                    $browser['titleKey'],
                    $browser['moduleName']
                );
            }
        }

        return $fields;
    }

    /**
     * @param \A17\Twill\Models\Contracts\TwillModelContract $object
     * @param array $fields
     * @param string $relationship
     * @param string $positionAttribute
     * @param string|null $browserName
     * @param array $pivotAttributes
     * @return void
     */
    public function updateApiBrowser(
        $object,
        $fields,
        $relationship,
        $positionAttribute = 'position',
        $browserName = null,
        $pivotAttributes = []
    ) {
        $browserName = $browserName ?? $relationship;
        $fieldsHasElements = isset($fields['browsers'][$browserName]) && !empty($fields['browsers'][$browserName]);
        $relatedElements = $fieldsHasElements ? $fields['browsers'][$browserName] : [];

        $relatedElementsWithPosition = [];
        $position = 1;

        foreach ($relatedElements as $relatedElement) {
            $relatedElementsWithPosition[$relatedElement['id']] = [$positionAttribute => $position++] + $pivotAttributes;
        }

        if ($object->$relationship() instanceof BelongsTo) {
            $isMorphTo = method_exists($object, $relationship) && $object->$relationship() instanceof MorphTo;

            $foreignKey = $object->$relationship()->getForeignKeyName();
            $id = Arr::get($relatedElements, '0.id');

            // Set the target id.
            $object->$foreignKey = $id;

            // If it is a morphTo, we also update the type.
            if ($isMorphTo) {
                $type = Arr::get($relatedElements, '0.endpointType');
                $object->{$object->$relationship()->getMorphType()} = $type;
            }

            $object->save();
        } elseif (
            $object->$relationship() instanceof HasOne ||
            $object->$relationship() instanceof HasMany
        ) {
            $this->updateBelongsToInverseApiBrowser($object, $relationship, $relatedElements);
        } else {
            $object->$relationship()->sync($relatedElementsWithPosition);
        }
    }

    private function updateBelongsToInverseApiBrowser($object, $relationship, $updatedElements)
    {
        $foreignKey = $object->$relationship()->getForeignKeyName();
        $relatedModel = $object->$relationship()->getRelated();
        $related = $this->getRelatedElementsAsCollection($object, $relationship);

        $relatedModel
            ->whereIn('id', $related->pluck('id'))
            ->update([$foreignKey => null]);

        $updated = $relatedModel
            ->whereIn('id', collect($updatedElements)->pluck('id'))
            ->get();

        if ($updated->isNotEmpty()) {
            $object->$relationship()->saveMany($updated);
        }
    }

    /**
     * @param \A17\Twill\Models\Model $object
     * @param array $fields
     * @param string $relationship
     * @param string $positionAttribute
     * @return void
     */
    public function updateOrderedBelongsTomany($object, $fields, $relationship, $positionAttribute = 'position')
    {
        $this->updateApiBrowser($object, $fields, $relationship, $positionAttribute);
    }

    /**
     * @param \A17\Twill\Models\Model $object
     * @param array $fields
     * @param string $browserName
     * @return void
     */
    public function updateRelatedBrowser($object, $fields, $browserName)
    {
        $object->saveRelated($fields['browsers'][$browserName] ?? [], $browserName);
    }

    /**
     * @param \A17\Twill\Models\Contracts\TwillModelContract $object
     * @param string $relation
     * @param string|null $routePrefix
     * @param string $titleKey
     * @param string|null $moduleName
     * @return array
     */
    public function getFormFieldsForApiBrowser(
        $object,
        $relation,
        $apiModel,
        $routePrefix = null,
        $titleKey = 'title',
        $moduleName = null
    ) {
        if (!$object->{$relation} || $object->{$relation}->isEmpty()) {
            return [];
        }

        $related = $this->getRelatedElementsAsCollection($object, $relation);

        if ($related->isEmpty()) {
            return [];
        }

        $isMorphTo = method_exists($object, $relation) && $object->$relation() instanceof MorphTo;
        if ($related->first() instanceof ApiRelation) {
            $ids = $object->{$relation}->pluck('datahub_id')->toArray();
            $related = $apiModel::query()->ids($ids)->get();
        }
        if ($related->first() instanceof BaseApiModel) {
            $browserData = $related->map(function ($relatedElement) use ($relation, $titleKey, $moduleName) {
                return $this->buildBrowserData($relation, $relatedElement, $titleKey, $moduleName);
            });
        } else {
            // Get all datahub_id's
            $ids = $related->pluck('datahub_id')->toArray();
            // Use those to load API records
            $apiElements = $apiModel::query()->ids($ids)->get();
            // Find locally selected objects
            $localApiMapping = $related->filter(function ($relatedElement) use ($apiElements) {
                return $apiElements->where('id', $relatedElement->datahub_id)->first();
            });
            $browserData = $localApiMapping->map(function ($relatedElement) use ($titleKey, $routePrefix, $relation, $moduleName, $apiElements) {
                // Get the API elements and use them to build the browser elements
                $apiElement = $apiElements->where('id', $relatedElement->datahub_id)->first();
                return $this->buildBrowserData($relation, $apiElement, $titleKey, $moduleName);
            });
        }
        return $browserData->values()->toArray();
    }

    /**
     * @param \A17\Twill\Models\Model $object
     * @param string $relation
     * @return array
     */
    public function getFormFieldsForRelatedApiBrowser($object, $relation, $titleKey = 'title')
    {
        return $object->getRelated($relation)->map(function ($relatedElement) use ($titleKey, $relation) {
            return ($relatedElement != null) ? [
                    'id' => $relatedElement->id,
                    'name' => $relatedElement->titleInBrowser ?? $relatedElement->$titleKey,
                    'endpointType' => $relatedElement->getMorphClass(),
            ] + $this->getDataEditAndThumbnailAttribute($relatedElement, null, $relation, null) : [];
        })->reject(function ($item) {
            return empty($item);
        })->values()->toArray();
    }

    private function getDataEditAndThumbnailAttribute($relatedElement, $routePrefix, $relation, $moduleName)
    {
        $data = [];

        // If it contains an augmented model create an edit link
        if (method_exists($relatedElement, 'hasAugmentedModel') && $relatedElement->hasAugmentedModel() && $relatedElement->getAugmentedModel()) {
            $data['edit'] = $relatedElement->adminEditUrl ?? moduleRoute($moduleName ?? $relation, $routePrefix ?? '', 'edit', [$relatedElement->getAugmentedModel()->id]);

            if (classHasTrait($relatedElement->getAugmentedModel(), \App\Models\Behaviors\HasMedias::class)) {
                $data['thumbnail'] = $relatedElement->getAugmentedModel()->defaultCmsImage(['w' => 100, 'h' => 100, 'fit' => 'crop']);
            }
        } else {
            // WEB-1187: This is reached after page refresh, if the model hasn't been augmented
            $data['edit'] = moduleRoute(
                $moduleName ?? $relation,
                $routePrefix ?? '',
                UrlHelpers::moduleRouteExists($moduleName ?? $relation, $routePrefix ?? '', 'augment') ? 'augment' : 'edit',
                [$relatedElement->id]
            );

            if ($relatedElement->hasAttribute('image_id')) {
                $data['thumbnail'] = DamsImageService::getTransparentFallbackUrl(['w' => 100, 'h' => 100]);
            }
        }

        return $data;
    }

    /**
     * Get all browser' detail info from the $browsers attribute.
     * The missing information will be inferred by convention of Twill.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getApiBrowsers()
    {
        return collect($this->apiBrowsers)->map(function ($browser, $key) {
            $browserName = is_string($browser) ? $browser : $key;
            $moduleName = empty($browser['moduleName']) ? $this->inferModuleNameFromBrowserName(
                $browserName
            ) : $browser['moduleName'];

            return [
                'relation' => empty($browser['relation']) ? $this->inferRelationFromBrowserName(
                    $browserName
                ) : $browser['relation'],
                'routePrefix' => isset($browser['routePrefix']) ? $browser['routePrefix'] : null,
                'titleKey' => empty($browser['titleKey']) ? 'title' : $browser['titleKey'],
                'moduleName' => $moduleName,
                'model' => empty($browser['model']) ? 'App\\Models\\Api\\' . $this->inferModelFromModuleName($moduleName) : $browser['model'],
                'positionAttribute' => empty($browser['positionAttribute']) ? 'position' : $browser['positionAttribute'],
                'browserName' => $browserName,
                'isApiRelation' => isset($browser['isApiRelation']) && $browser['isApiRelation'] == true,
            ];
        })->values();
    }

    /**
     * Guess the browser's relation name (shoud be lower camel case, ex. userGroup, contactOffice).
     */
    protected function inferRelationFromBrowserName(string $browserName): string
    {
        return Str::camel($browserName);
    }

    /**
     * Guess the module's model name (should be singular upper camel case, ex. User, ArticleType).
     */
    protected function inferModelFromModuleName(string $moduleName): string
    {
        return Str::studly(Str::singular($moduleName));
    }

    /**
     * Guess the browser's module name (should be plural lower camel case, ex. userGroups, contactOffices).
     */
    protected function inferModuleNameFromBrowserName(string $browserName): string
    {
        return Str::camel(Str::plural($browserName));
    }

    private function getRelatedElementsAsCollection($object, $relation)
    {
        return collect(
            $object->$relation instanceof BaseApiModel ? [$object->$relation] : $object->$relation
        );
    }

    /**
     * The same as the normal ordered update with the difference that this one adds a relation to the pivot
     * and it creates new models per each new relation as we don't have both ends of the polymorphic relation
     * This is done this way so we can reuse the same functions and table for all API browsers.
     */
    public function updateBrowserApiRelated($object, $fields, $relationship, $positionAttribute = 'position')
    {
        $relatedElementsWithPosition = [];

        $fieldsHasElements = isset($fields['browsers'][$relationship]) && !empty($fields['browsers'][$relationship]);
        $relatedElements = $fieldsHasElements ? $fields['browsers'][$relationship] : [];

        // If we don't have an element to save the datahub_id, let's create one
        $relatedElements = array_map(function ($element) {
            return ApiRelation::firstOrCreate(['datahub_id' => $element['id']]);
        }, $relatedElements);

        $position = 1;

        foreach ($relatedElements as $relatedElement) {
            $relatedElementsWithPosition[$relatedElement['id']] = [
                // Add the relationship to the pivot, this way we can use this browser several times per model
                'relation' => $relationship,
                $positionAttribute => $position++
            ];
        }
        $object->{$relationship}()->detach($object->{$relationship}->pluck('id'));
        $object->{$relationship}()->attach($relatedElementsWithPosition);
    }

    public function updateMultiBrowserApiRelated($object, $fields, $relationship, $typeUsesApi)
    {
        // WEB-2272: check if we dont leave some stale data in database by not deleting apiElements
        // Remove all associations
        // $object->apiElements()->detach();

        $relatedElementsWithPosition = [];

        $fieldsHasElements = isset($fields['browsers'][$relationship]) && !empty($fields['browsers'][$relationship]);
        $relatedElements = $fieldsHasElements ? $fields['browsers'][$relationship] : [];
        // If we don't have an element to save the datahub_id, let's create one
        $relatedElements = array_map(function ($element) use ($typeUsesApi) {
            if ($typeUsesApi[$element['endpointType']]) {
                $apiItem = ApiRelation::firstOrCreate(['datahub_id' => $element['id']]);
                $apiItem->endpointType = $element['endpointType'];

                return $apiItem;
            }

            return $element;
        }, $relatedElements);

        RelatedItem::where([
            'browser_name' => $relationship,
            'subject_id' => $object->getKey(),
            'subject_type' => $object->getMorphClass(),
        ])->delete();

        $position = 1;
        collect($relatedElements)->each(function ($values) use ($relationship, &$position, $object) {
            RelatedItem::create([
                'subject_id' => $object->getKey(),
                'subject_type' => $object->getMorphClass(),
                'related_id' => $values['id'],
                'related_type' => $values['endpointType'],
                'browser_name' => $relationship,
                'position' => $position,
            ]);
            $position++;
        });
    }

    protected function buildBrowserData($relation, $apiElement, $titleKey, $moduleName)
    {
        $data = [];
        // If it contains an augmented model create an edit link
        if ($apiElement->hasAugmentedModel() && $apiElement->getAugmentedModel()) {
            $data['edit'] = moduleRoute($moduleName ?? $relation, $routePrefix ?? '', 'edit', [$apiElement->getAugmentedModel()->id]);

            if (classHasTrait($apiElement->getAugmentedModel(), \App\Models\Behaviors\HasMedias::class)) {
                $data['thumbnail'] = $apiElement->getAugmentedModel()->defaultCmsImage(['w' => 100, 'h' => 100]);
            }
        } else {
            // WEB-1187: This is reached after page refresh, if the model hasn't been augmented
            if (UrlHelpers::moduleRouteExists($moduleName ?? $relation, $routePrefix ?? '', 'augment')) {
                $data['edit'] = moduleRoute($moduleName ?? $relation, $routePrefix ?? '', 'augment', [$apiElement->id]);
            }

            if ($apiElement->hasAttribute('image_id')) {
                $data['thumbnail'] = DamsImageService::getTransparentFallbackUrl(['w' => 100, 'h' => 100]);
            }
        }

        return [
            'id' => $apiElement->id,
            'name' => $apiElement->titleInBrowser ?? $apiElement->{$titleKey},
        ] + $data;
    }

    public function getFormFieldsForMultiBrowserApi($object, $browser_name, $apiModelsDefinitions, $typeUsesApi)
    {
        $results = collect();

        $typedFormFields = $object->relatedItems
            ->where('browser_name', $browser_name)
            ->groupBy('related_type')
            ->map(function ($items, $type) use ($apiModelsDefinitions, $browser_name, $typeUsesApi) {
                if ($typeUsesApi[$type]) {
                    $apiElements = $this->getApiElements($items, $type, $apiModelsDefinitions);
                    $localApiMapping = $this->getLocalApiMapping($items, $apiElements);
                    $apiModelDefinition = $apiModelsDefinitions[$type];

                    return $localApiMapping->map(function ($relatedElement) use ($apiModelDefinition, $apiElements) {
                        $data = [];
                        // Get the API elements and use them to build the browser elements
                        $apiRelationElement = \App\Models\ApiRelation::where('id', $relatedElement->related_id)->first();
                        $apiElement = $apiElements->where('id', $apiRelationElement->datahub_id)->first();

                        // If it contains an augmented model create an edit link
                        if ($apiElement->hasAugmentedModel() && $apiElement->getAugmentedModel()) {
                            $data['edit'] = moduleRoute($apiModelDefinition['moduleName'], $apiModelDefinition['routePrefix'] ?? '', 'edit', [$apiElement->getAugmentedModel()->id]);

                            if (classHasTrait($apiElement->getAugmentedModel(), \App\Models\Behaviors\HasMedias::class)) {
                                $data['thumbnail'] = $apiElement->getAugmentedModel()->defaultCmsImage(['w' => 100, 'h' => 100]);
                            }
                        }
                        // WEB-1187: Add augment route here!

                        return [
                            'id' => $apiElement->id,
                            'name' => $apiElement->titleInBrowser ?? $apiElement->title,
                            'endpointType' => $apiModelDefinition['moduleName'],
                            'position' => $relatedElement->position
                        ] + $data;
                    })->values()->toArray();
                } else {
                    return $items->map(function ($relatedElement) {
                        $element = $relatedElement->related;
                        $elementPosition = $relatedElement->position;

                        if ($element) {
                            return [
                                'id' => $element->id,
                                'name' => $element->titleInBrowser ?? $element->title,
                                'endpointType' => $element->getMorphClass(),
                                'position' => $elementPosition,
                                'edit' => $element->adminEditUrl,
                            ] + ((classHasTrait($element, \A17\Twill\Models\Behaviors\HasMedias::class)) ? [
                                'thumbnail' => $element->defaultCmsImage(['w' => 100, 'h' => 100]),
                            ] : []);
                        }
                    });
                }
            });

        return $typedFormFields->flatten(1)->sortBy(function ($browserItem, $key) {
            return $browserItem['position'];
        })->values()->toArray();
    }
}
