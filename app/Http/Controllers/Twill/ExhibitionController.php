<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\Checkbox;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Boolean;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;

class ExhibitionController extends BaseApiController
{
    protected $moduleName = 'exhibitions';
    protected $hasAugmentedModel = true;

    public function setUpController(): void
    {
        parent::setUpController();

        $this->setSearchColumns(['title']);

        $this->enableFeature();
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        $columns = new TableColumns();
        $columns->add(
            Boolean::make()
                ->field('is_featured')
        );
        $columns->add(
            Text::make()
                ->field('image_url')
                ->optional()
                ->hide()
        );
        return $columns;
    }
    public function additionalFormFields($exhibition, $apiExhibition): Form
    {
        $apiValues = $apiExhibition->getAttributes();
        $fields = new Form();
        $fields->add(
            Checkbox::make()
                ->name('is_featured')
                ->default($apiValues['is_featured'])
        );
        $fields->add(
            Input::make()
                ->name('image_url')
                ->placeholder($apiValues['image_url'])
        );
        return $fields;
    }
}
