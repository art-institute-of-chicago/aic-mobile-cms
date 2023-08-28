<?php

namespace App\Http\Controllers\Twill\Columns;

use A17\Twill\Exceptions\ColumnMissingPropertyException;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Listings\TableColumn;
use Illuminate\Support\Str;

class RelationCount extends TableColumn
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
        $count = $model->{$this->relation}()->count();
        return $count . ' ' . ($count === 1 ? Str::singular($this->field) : Str::plural($this->field));
    }
}
