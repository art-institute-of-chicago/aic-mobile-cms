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
        $this->disablePublish();
        $this->disableBulkPublish();
        $this->setModuleName('floors');
    }

    public function additionalFormFields(TwillModelContract $object): Form
    {
        return parent::additionalFormFields($object)
            ->add(
                Files::make()
                    ->name('floor_plan')
            );
    }
}
