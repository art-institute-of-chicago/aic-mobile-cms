<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;

class ExhibitionController extends BaseApiController
{
    protected $moduleName = 'exhibitions';
    protected $hasAugmentedModel = true;

    public function setUpController(): void
    {
        $this->setSearchColumns(['title']);
        $this->disableBulkDelete();
        $this->disableBulkEdit();
        $this->disableBulkPublish();
        $this->disableCreate();
        $this->disableDelete();
        $this->disableEdit();
        $this->disablePublish();
        $this->disableRestore();
    }

    protected function getIndexTableColumns(): TableColumns
    {
        $columns = new TableColumns();
        $columns->add(
            Text::make()
                ->field('id')
                ->title('Datahub Id')
                ->optional()
                ->hide()
        );
        $columns->add(
            Text::make()
                ->field('title')
        );
        $columns->add(
            Text::make()
                ->field('image_url')
                ->optional()
        );
        return $columns;
    }

    public function getForm(TwillModelContract $exhibition): Form
    {
        $form = Form::make();
        $form->add(
            Input::make()
                ->name('datahub_id')
                ->disabled()
        );
        $form->add(
            Input::make()
                ->name('title')
        );
        $form->add(
            Input::make()
                ->name('image_url')
        );
        return $form;
    }
}
