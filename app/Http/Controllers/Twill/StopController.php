<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\Browser;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Relation;
use App\Http\Controllers\Twill\Columns\ApiRelation;

class StopController extends BaseController
{
    protected function setUpController(): void
    {
        parent::setUpController();
        $this->setModuleName('stops');
        $this->setSearchColumns(['title', 'selector_number']);
    }

    /**
     * Place the Selector Number before the title.
     */
    protected function getIndexTableColumns(): TableColumns
    {
        $columns = parent::getIndexTableColumns();
        $after = $columns->splice(2);
        $columns->push(
            Text::make()
                ->field('selector_number')
                ->sortable()
        );
        return $columns->merge($after);
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        return parent::additionalIndexTableColumns()
            ->add(
                ApiRelation::make()
                    ->field('sound_id')
                    ->title('Audio')
                    ->relation('audio')
                    ->sortable()
            )
            ->add(
                ApiRelation::make()
                    ->field('artwork_id')
                    ->title('Object')
                    ->relation('object')
                    ->sortable()
            )
            ->add(
                Relation::make()
                    ->field('truncated_title')
                    ->title('Tour(s)')
                    ->relation('tours')
                    ->optional()
            );
    }

    protected function additionalBrowserTableColumns(): TableColumns
    {
        return parent::additionalBrowserTableColumns()
            ->add(
                Text::make()
                    ->field('selector_number')
            );
    }

    public function additionalFormFields(TwillModelContract $stop): Form
    {
        return parent::additionalFormFields($stop)
            ->add(
                Input::make()
                    ->name('selector_number')
                    ->type('number')
                    ->min(10)
                    ->max(999)
                    ->default(10)
                    ->required()
            )
            ->add(
                Browser::make()
                    ->name('audios')
                    ->modules([\App\Models\Api\Sound::class])
                    ->note('Add audios to stop')
                    ->sortable(false)
                    ->max(99)
            )
            ->add(
                Browser::make()
                    ->name('object')
                    ->modules([\App\Models\Artwork::class])
            )
            ->add(
                Input::make()
                    ->name('artwork_id')
                    ->label('Object Id')
                    ->required()
                    ->note('Object Datahub Id')
            )
            ->add(
                Browser::make()
                    ->name('tour_stops')
                    ->label('Tours')
                    ->modules([\App\Models\Tour::class])
                    ->note('Add stop to tours if applicable')
                    ->sortable(false)
                    ->max(99)
            );
    }
}
