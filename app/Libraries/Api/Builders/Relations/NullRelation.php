<?php

namespace App\Libraries\Api\Builders\Relations;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;

class NullRelation extends Relation
{
    public function addConstraints()
    {
        // noop
    }

    public function addEagerConstraints(array $models)
    {
        // noop
    }

    public function initRelation(array $models, $relation)
    {
        return [];
    }

    public function match(array $models, Collection $results, $relation)
    {
        return [];
    }

    public function getResults()
    {
        return;
    }
}
