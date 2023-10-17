<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\Files;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use A17\Twill\Services\Forms\Form;

class MapFloorController extends BaseController
{
    protected function setUpController(): void
    {
        parent::setUpController();
        $this->disablePublish();
        $this->disableBulkPublish();
        $this->setModuleName('mapFloors');
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        return parent::additionalIndexTableColumns()
            ->add(
                Text::make()
                    ->field('anchor_pixel_1')
            )
            ->add(
                Text::make()
                    ->field('anchor_pixel_2')
            )
            ->add(
                Text::make()
                    ->field('anchor_location_1')
            )
            ->add(
                Text::make()
                    ->field('anchor_location_2')
            );
    }

    public function additionalFormFields(TwillModelContract $object): Form
    {
        return parent::additionalFormFields($object)
            ->add(
                Files::make()
                    ->name('floor_plan')
            )
            ->add(
                Input::make()
                    ->name('anchor_pixel_1')
            )
            ->add(
                Input::make()
                    ->name('anchor_pixel_2')
            )
            ->add(
                Input::make()
                    ->name('anchor_location_1')
            )
            ->add(
                Input::make()
                    ->name('anchor_location_2')
            );
    }
}