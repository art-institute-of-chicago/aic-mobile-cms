<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\Browser;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Relation;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use App\Http\Controllers\Twill\Columns\ApiRelation;

class SelectorController extends BaseController
{
    protected function setUpController(): void
    {
        parent::setUpController();
        $this->disablePublish();
        $this->setModuleName('selectors');
        $this->setSearchColumns(['number']);
        $this->setTitleColumnKey('number');
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        return parent::additionalIndexTableColumns()
            ->add(
                Text::make()
                    ->field('object_datahub_id')
                    ->title('Object Id')
            )
            ->add(
                Text::make()
                    ->field('object_title')
                    ->title('Object')
            )
            ->add(
                Text::make()
                    ->field('tour_title')
                    ->title('Tour')
                    ->sortable()
            )
            ->add(
                Text::make()
                    ->field('locales')
                    ->title('Languages')
                    ->optional()
                    ->hide()
            )
            ->add(
                Text::make()
                    ->field('notes')
                    ->optional()
                    ->hide()
            );
    }

    protected function additionalFormFields(TwillModelContract $selector): Form
    {
        return parent::additionalFormFields($selector)
            ->add(
                Browser::make()
                    ->name('audio')
                    ->modules([\App\Models\Api\Audio::class])
                    ->max(count(getLocales()))
                    ->sortable(false)
            )
            ->add(
                Browser::make()
                    ->name('selectables')
                    ->label('Tour / Stop')
                    ->modules([\App\Models\Tour::class, \App\Models\Stop::class])
                    ->note('Associate with a tour or a stop')
                    ->sortable(false)
            )
            ->add(
                Browser::make()
                    ->name('objects')
                    ->label('Object')
                    ->modulesCustom([
                        [
                            'name' => 'collectionObjects',
                            'label' => 'Collection Objects',
                            'params' => ['artwork_id' => $selector->object_id],
                        ],
                        [
                            'name' => 'loanObjects',
                            'label' => 'Loan Objects',
                        ],
                    ])
            )
            ->add(
                Input::make()
                    ->name('notes')
                    ->type('textarea')
            );
    }
}
