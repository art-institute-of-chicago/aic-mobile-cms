<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleTranslations;
use A17\Twill\Repositories\Behaviors\HandleMedias;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\Annotation;

class AnnotationRepository extends ModuleRepository
{
    use HandleTranslations, HandleMedias;

    public function __construct(Annotation $annotation)
    {
        $this->model = $annotation;
    }

    public function getFormFields($annotation): array
    {
        $fields = parent::getFormFields($annotation);
        $fields['browsers']['floors'] = $this->getFormFieldsForBrowser($annotation, 'floor', moduleName: 'floors');
        $fields['browsers']['types'] = $this->getFormFieldsForBrowser(
            $annotation,
            'types',
            moduleName: 'annotationTypes',
        );
        return $fields;
    }

    public function afterSave($annotation, array $fields): void
    {
        $this->updateBrowser($annotation, $fields, 'floor', browserName: 'floors');
        $this->updateBrowser($annotation, $fields, 'types', browserName: 'types');
        parent::afterSave($annotation, $fields);
    }
}
