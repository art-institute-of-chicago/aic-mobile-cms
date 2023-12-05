<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleTranslations;
use App\Models\AnnotationCategory;

class AnnotationCategoryRepository extends ModuleRepository
{
    use HandleTranslations;

    public function __construct(AnnotationCategory $category)
    {
        $this->model = $category;
    }

    public function getFormFields($category): array
    {
        $fields = parent::getFormFields($category);
        $fields['browsers']['types'] = $this->getFormFieldsForBrowser($category, 'types', moduleName: 'annotationTypes');
        return $fields;
    }

    public function afterSave($category, array $fields): void
    {
        $this->updateBrowser($category, $fields, 'types', browserName: 'types');
        parent::afterSave($category, $fields);
    }
}
