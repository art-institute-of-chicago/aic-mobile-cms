<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleMedias;
use App\Models\LoanObject;

class LoanObjectRepository extends ModuleRepository
{
    use HandleMedias;

    protected $apiBrowsers = [
        'gallery' => [
            'moduleName' => 'galleries',
        ]
    ];

    public function __construct(LoanObject $loan)
    {
        $this->model = $loan;
    }
}
