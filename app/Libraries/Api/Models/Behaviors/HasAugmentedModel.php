<?php

namespace App\Libraries\Api\Models\Behaviors;

trait HasAugmentedModel
{
    protected $augmentedModel = null;
    protected $augmentedModelClass;

    public function setAugmentedModel($model)
    {
        $this->augmentedModel = $model;
        $this->setAttribute('is_augmented', true);
    }

    public function getAugmentedModelClass()
    {
        return $this->augmentedModelClass;
    }

    public function getAugmentedModel()
    {
        $this->setAttribute('is_augmented', false);
        if ($this->augmentedModel) {
            $this->setAttribute('is_augmented', true);
            return $this->augmentedModel;
        }
        if ($this->augmentedModel = $this->augmentedModelClass::where('datahub_id', $this->id)->first()) {
            $this->setAttribute('is_augmented', true);
            return $this->augmentedModel;
        }
        return $this->augmentedModel;
    }

    public function hasAugmentedModel(): bool
    {
        return (bool) $this->getAugmentedModel();
    }

    public function getIsAugmentedAttribute(): bool
    {
        return $this->hasAugmentedModel();
    }

    /**
     * Bypass missed methods to the augmented model if existent
     *
     */
    public function __call($method, $parameters): mixed
    {
        if (method_exists($this, $method)) {
            return $this->{$method}($parameters);
        }

        if ($this->hasAugmentedModel() && method_exists($this->getAugmentedModel(), $method)) {
            return $this->getAugmentedModel()->{$method}(...$parameters);
        }

        return null;
    }
}
