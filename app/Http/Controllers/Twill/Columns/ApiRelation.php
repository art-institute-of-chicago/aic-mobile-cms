<?php

namespace App\Http\Controllers\Twill\Columns;

use A17\Twill\Exceptions\ColumnMissingPropertyException;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Listings\Columns\Relation;

class ApiRelation extends Relation
{
    private ?string $relation = null;

    public function relation(string $relation): static
    {
        $this->relation = $relation;
        return $this;
    }

    protected function getRenderValue(TwillModelContract $model): string
    {
        if (null === $this->relation) {
            throw new ColumnMissingPropertyException('Relation column missing relation value: ' . $this->field);
        }
        if (method_exists($model, 'getAugmentedModel') && $augmentedModel = $model->getAugmentedModel()) {
            if ($relation = $augmentedModel->{$this->relation}()) {
                return $relation;
            }
        }
        $relation = $model->{$this->relation}();
        return (string) $relation;
    }
}
