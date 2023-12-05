<?php

namespace App\Models\Behaviors;

use A17\Twill\Models\Contracts\TwillModelContract;

/**
 * Trait used to augment the local entity with Data coming from the API.
 * This is only to be used at the CMS when editing an augmented resource
 * in order to show all API data as well.
 */
trait HasApiModel
{
    protected $apiModel = null;

    private array $apiFields = [];

    /**
     * Refresh the model with API values in case it's not done yet.
     */
    public function refreshApi(): self
    {
        $this->getApiModel();
        $this->augmentWithApiModel();
        return $this;
    }

    public function getApiModel()
    {
        if (!$this->apiModel) {
            $this->setApiModel($this->apiModelClass::query()->find($this->datahub_id));
        }
        return $this->apiModel;
    }

    public function setApiModel(TwillModelContract $model)
    {
        $this->apiModel = $model;
    }

    public function getTitleAttribute(): string
    {
        return (string) ($this->attributes['title'] ?? $this->getApiModel()?->getAttribute('title'));
    }

    /**
     * Augment the entity with the values coming from the API.
     * TODO: Solve name collisions
     */
    public function augmentWithApiModel()
    {
        foreach ($this->apiModel->toArray() as $key => $value) {
            if ($this->hasAttribute($key)) {
                // TODO: If the attribute already exists un-tie with a mapping array and set the attr.
                // Something like ['id' => 'datahub_id']
            } else {
                $this->setAttribute($key, $value);
                array_push($this->apiFields, $key);
            }
        }
    }

    public function hasAttribute($attr): bool
    {
        return array_key_exists($attr, $this->attributes) && !is_null($this->attributes[$attr]);
    }

    public function getApiField($field)
    {
        return $this->getApiFields[$field];
    }

    /**
     * Get API fields and its values stored at the object
     */
    public function getApiFields(): object
    {
        return (object) array_reduce($this->apiFields, function ($result, $field) {
            $result[$field] = $this->{$field};

            return $result;
        }, []);
    }
}
