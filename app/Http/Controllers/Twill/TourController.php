<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Services\Forms\Fields\Browser;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Relation;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use App\Http\Controllers\Twill\Columns\ApiRelation;
use App\Http\Controllers\Twill\Columns\RelationCount;
use App\Models\Selector;
use App\Models\Sound;
use App\Models\Tour;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

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
                Relation::make()
                    ->field('number')
                    ->title('Selector Number')
                    ->relation('selector')
            )
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

    public function additionalFormFields($tour): Form
    {
        return parent::additionalFormFields($tour)
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
                    ->name('selectors')
                    ->label('Selector')
                    ->modules([Selector::class])
                    ->modulesCustom([
                        [
                            'name' => 'selectors',
                            'params' => ['selector_id' => $tour->selector?->id],
                            'routePrefix' => null,
                        ]
                    ])
            )
            ->add(
                Browser::make()
                    ->name('gallery')
                    ->modules(['gallery'])
                    ->label('Gallery')
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

    public function creatWithAudio()
    {
        $audio = Sound::find(request('sound_id'));
        $tour = Tour::create();
        $tour->selector()->save($audio->selector);
        return Redirect::to(moduleRoute($this->moduleName, $this->routePrefix, 'edit', ['tour' => $tour->id]));
    }
}
