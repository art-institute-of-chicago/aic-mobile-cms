<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\Browser;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Relation;
use A17\Twill\Services\Listings\TableColumns;

class AnnotationCategoryController extends BaseController
{
    protected function setUpController(): void
    {
        parent::setUpController();
        $this->disableBulkPublish();
        $this->disableCreate();
        $this->disableDelete();
        $this->disablePublish();
        $this->disableRestore();
        $this->setModuleName('annotationCategories');
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        return parent::additionalIndexTableColumns()
            ->add(
                Relation::make()
                    ->field('title')
                    ->title('Types')
                    ->relation('types')
            );
    }

    public function additionalFormFields(TwillModelContract $object): Form
    {
        return parent::additionalFormFields($object)
            ->add(
                Browser::make()
                    ->name('types')
                    ->label('Types')
                    ->modules([\App\Models\AnnotationType::class])
                    ->sortable(false)
                    ->max(\App\Models\AnnotationType::count())
            );
    }
}
