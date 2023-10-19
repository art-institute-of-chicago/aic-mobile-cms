<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Services\Forms\Fields\Browser;
use A17\Twill\Services\Listings\Columns\Relation;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Listings\TableColumns;
use A17\Twill\Services\Forms\Form;

class AnnotationTypeController extends BaseController
{
    protected function setUpController(): void
    {
        parent::setUpController();
        $this->disableBulkPublish();
        $this->disableCreate();
        $this->disableDelete();
        $this->disablePublish();
        $this->disableRestore();
        $this->setModuleName('annotationTypes');
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        return parent::additionalIndexTableColumns()
            ->add(
                Relation::make()
                    ->field('title')
                    ->title('Category')
                    ->relation('category')
            );
    }

    public function additionalFormFields(TwillModelContract $object): Form
    {
        return parent::additionalFormFields($object)
            ->add(
                Browser::make()
                    ->name('categories')
                    ->label('Category')
                    ->modules([\App\Models\AnnotationCategory::class])
            );
    }
}
