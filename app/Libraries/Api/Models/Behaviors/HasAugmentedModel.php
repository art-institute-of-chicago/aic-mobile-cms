<?php

namespace App\Libraries\Api\Models\Behaviors;

trait HasAugmentedModel
{
    protected $augmentedModel = null;
    protected $augmentedModelClass;

    public function setAugmentedModel($model)
    {
        $this->augmentedModel = $model;
    }

    public function getAugmentedModelClass()
    {
        return $this->augmentedModelClass;
    }

    public function getAugmentedModel()
    {
        if ($this->augmentedModel) {
            return $this->augmentedModel;
        }

        $this->augmentedModel = $this->augmentedModelClass::where('datahub_id', $this->id)->first();

        return $this->augmentedModel;
    }

    public function hasAugmentedModel(): bool
    {
        return (bool) $this->augmentedModel;
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
