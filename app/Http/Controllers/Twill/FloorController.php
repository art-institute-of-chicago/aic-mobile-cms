<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\Files;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use A17\Twill\Services\Forms\Form;

class FloorController extends BaseController
{
    protected function setUpController(): void
    {
        parent::setUpController();
        $this->disableBulkPublish();
        $this->disableCreate();
        $this->disableDelete();
        $this->disablePublish();
        $this->disableRestore();
        $this->setModuleName('floors');
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        return parent::additionalIndexTableColumns()
            ->add(
                Text::make()
                    ->field('level')
                    ->sortable()
            )
            ->add(
                Text::make()
                    ->field('geo_id')
                    ->optional()
                    ->hide()
            );
    }

    public function additionalFormFields(TwillModelContract $object): Form
    {
        return parent::additionalFormFields($object)
            ->add(
                Files::make()
                    ->name('floor_plan')
                    ->translatable(false)
            )
            ->add(
                Input::make()
                    ->name('level')
            )
            ->add(
                Input::make()
                    ->name('geo_id')
                    ->note('Internal use only')
            );
    }
}
