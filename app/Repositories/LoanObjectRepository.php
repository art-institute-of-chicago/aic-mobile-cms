<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleMedias;
use App\Models\LoanObject;

class LoanObjectRepository extends ModuleRepository
{
    // use HandleMedias;

    public function __construct(LoanObject $loan)
    {
        $this->model = $loan;
    }

    public function getFormFields($object): array
    {
        $fields = parent::getFormFields($object);
        $fields['browsers']['gallery'] = $this->getFormFieldsForBrowserApi(
            $object,
            relation: 'gallery',
            apiModel: \App\Models\Api\Gallery::class,
            moduleName: 'galleries',
        );
        return $fields;
    }

    public function afterSave($object, array $fields): void
    {
        $this->updateBrowserApiRelated($object, $fields, 'gallery');
        parent::afterSave($object, $fields);
    }
}
