<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleTranslations;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\AnnotationType;

class AnnotationTypeRepository extends ModuleRepository
{
    use HandleTranslations;

    public function __construct(AnnotationType $type)
    {
        $this->model = $type;
    }

    public function getFormFields($type): array
    {
        $fields = parent::getFormFields($type);
        $fields['browsers']['categories'] = $this->getFormFieldsForBrowser(
            $type,
            'category',
            moduleName: 'annotationCategories',
        );
        return $fields;
    }

    public function afterSave($type, array $fields): void
    {
        $this->updateBrowser($type, $fields, 'category', browserName: 'categories');
        parent::afterSave($type, $fields);
    }
}
