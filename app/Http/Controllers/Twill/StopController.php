<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\BaseFormField;
use A17\Twill\Services\Forms\Fields\Browser;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Relation;
use A17\Twill\Services\Listings\TableColumns;
use App\Models\Selector;

class StopController extends BaseController
{
    protected function setUpController(): void
    {
        parent::setUpController();
        $this->setModuleName('stops');
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        return parent::additionalIndexTableColumns()
            ->add(
                Relation::make()
                    ->field('title')
                    ->title('Tour')
                    ->relation('tours')
            )
            ->add(
                Relation::make()
                    ->field('number')
                    ->title('Selector Number')
                    ->relation('selector')
            );
    }

    protected function additionalBrowserTableColumns(): TableColumns
    {
        return parent::additionalBrowserTableColumns()
            ->add(
                Relation::make()
                    ->field('number')
                    ->title('Selector Number')
                    ->relation('selector')
            );
    }

    public function additionalFormFields(TwillModelContract $stop): Form
    {
        return parent::additionalFormFields($stop)
            ->add(
                Browser::make()
                    ->name('selectors')
                    ->label('Selector')
                    ->modules([Selector::class])
                    ->modulesCustom([
                        [
                            'name' => 'selectors',
                            'params' => ['selector_id' => $stop->selector?->id],
                        ]
                    ])
            )
            ->add(
                Browser::make()
                    ->name('tour_stops')
                    ->label('Tour')
                    ->modules([\App\Models\Tour::class])
                    ->note('Add stop to tour')
                    ->sortable(false)
            );
    }

    protected function getTitleField(): BaseFormField
    {
        return parent::getTitleField()
            ->note('Title of related object');
    }
}
