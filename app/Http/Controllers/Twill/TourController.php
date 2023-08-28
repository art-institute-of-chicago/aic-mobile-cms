<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Services\Forms\Fields\Browser;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use App\Http\Controllers\Twill\Columns\ApiRelation;
use App\Http\Controllers\Twill\Columns\RelationCount;

class TourController extends BaseController
{
    protected function setUpController(): void
    {
        parent::setUpController();
        $this->enableReorder();
        $this->setModelName('Tour');
        $this->setModuleName('tours');
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        return parent::additionalIndexTableColumns()
            ->add(
                Text::make()
                    ->field('description')
                    ->optional()
                    ->hide()
            )
            ->add(
                ApiRelation::make()
                    ->field('gallery_id')
                    ->title('Gallery')
                    ->relation('gallery')
                    ->hide()
            )
            ->add(
                Text::make()
                    ->field('selector_number')
                    ->optional()
                    ->hide()
            )
            ->add(
                ApiRelation::make()
                    ->field('Sound_id')
                    ->title('Audio')
                    ->relation('audio')
                    ->optional()
                    ->hide()
            )
            ->add(
                RelationCount::make()
                    ->field('stops')
                    ->relation('stops')
                    ->optional()
            )
            ->add(
                Text::make()
                    ->field('duration_in_minutes')
                    ->title('Duration')
                    ->optional()
            );
    }

    public function additionalFormFields($model): Form
    {
        return parent::additionalFormFields($model)
            ->add(
                Input::make()
                    ->name('image_url')
                    ->label('Image')
                    ->disabled()
                    ->note('Coming Soon!')
            )
            ->add(
                Input::make()
                    ->name('description')
                    ->type('textarea')
                    ->required()
                    ->translatable()
            )
            ->add(
                Browser::make()
                    ->name('gallery')
                    ->modules(['gallery'])
                    ->label('Gallery')
            )
            ->add(
                Input::make()
                    ->name('selector_number')
                    ->type('number')
                    ->required()
            )
            ->add(
                Input::make()
                    ->name('sound_id')
                    ->label('Audio Id')
                    ->translatable()
                    ->required()
                    ->note('Audio Id')
            )
            ->add(
                Input::make()
                    ->name('duration')
                    ->type('number')
                    ->min(1)
                    ->default(1)
                    ->required()
                    ->note('in minutes')
            )
            ->add(
                Browser::make()
                    ->name('tour_stops')
                    ->modules([\App\Models\Stop::class])
                    ->note('Add stops to tour')
                    ->sortable()
                    ->max(99)
            );
    }
}
