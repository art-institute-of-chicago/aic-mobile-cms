<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleMedias;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\LoanObject;

class LoanObjectRepository extends ModuleRepository
{
    // use HandleMedias;

    public function __construct(LoanObject $loan)
    {
        $this->model = $loan;
    }
}
