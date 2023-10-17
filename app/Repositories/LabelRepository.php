<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleTranslations;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\Label;
use Illuminate\Database\Eloquent\Builder;

class LabelRepository extends ModuleRepository
{
    use HandleTranslations;

    public function __construct(Label $label)
    {
        $this->model = $label;
    }

    public function order(Builder $query, array $orderBy = []): Builder
    {
        if (array_key_exists('title', $orderBy)) {
            $query->orderByTitle($orderBy['title']);
            unset($orderBy['title']);
        }
        return parent::order($query, $orderBy);
    }
}
