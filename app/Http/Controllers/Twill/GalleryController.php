<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\Checkbox;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\Filters\BasicFilter;
use A17\Twill\Services\Listings\Filters\TableFilters;
use A17\Twill\Services\Listings\TableColumns;
use App\Models\Api\Gallery;
use Illuminate\Database\Eloquent\Builder;

class GalleryController extends BaseApiController
{
    protected $moduleName = 'galleries';
    protected $hasAugmentedModel = false;

    public function setUpController(): void
    {
        parent::setUpController();

        $this->setSearchColumns(['title', 'floor', 'number']);
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        $columns = new TableColumns();
        $columns->add(
            Text::make()
                ->field('floor')
                ->optional()
        );
        $columns->add(
            Text::make()
                ->field('number')
                ->optional()
        );
        $columns->add(
            Text::make()
                ->field('is_closed')
                ->title('Open/Closed')
                ->customRender(function (Gallery $gallery) {
                    return $gallery->is_closed ? 'Closed' : 'Open';
                })
                ->sortable()
        );
        $columns->add(
            Text::make()
                ->field('latitude')
                ->customRender(function (Gallery $gallery) {
                    return number_format((float) $gallery->latitude, 13);
                })
                ->optional()
                ->hide()
        );
        $columns->add(
            Text::make()
                ->field('longitude')
                ->customRender(function (Gallery $gallery) {
                    return number_format((float) $gallery->longitude, 13);
                })
                ->optional()
                ->hide()
        );
        return $columns;
    }

    public function additionalFormFields($model, $apiModel): Form
    {
        $fields = new Form();
        $fields->add(
            Input::make()
                ->name('title')
                ->disabled()
        );
        $fields->add(
            Input::make()
                ->name('floor')
                ->disabled()
        );
        $fields->add(
            Input::make()
                ->name('number')
                ->disabled()
        );
        $fields->add(
            Checkbox::make()
                ->name('is_closed')
                ->disabled()
        );
        return $fields;
    }
}
