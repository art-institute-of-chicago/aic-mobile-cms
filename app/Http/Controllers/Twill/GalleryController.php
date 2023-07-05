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
        $this->setSearchColumns(['title', 'floor', 'number']);
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
        $columns->add(
            Text::make()
                ->field('source_updated_at')
                ->optional()
                ->hide()
        );
        $columns->add(
            Text::make()
                ->field('updated_at')
                ->title('API Updated At')
                ->optional()
                ->hide()
        );
        return $columns;
    }

    public function getForm(TwillModelContract $gallery): Form
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
                ->disabled()
        );
        $form->add(
            Input::make()
                ->name('floor')
                ->disabled()
        );
        $form->add(
            Input::make()
                ->name('number')
                ->disabled()
        );
        $form->add(
            Checkbox::make()
                ->name('is_closed')
                ->disabled()
        );
        return $form;
    }
}
