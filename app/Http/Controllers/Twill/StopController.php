<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\Browser;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Relation;
use A17\Twill\Services\Listings\TableColumns;
use App\Http\Controllers\Twill\Columns\ApiRelation;
use App\Models\Audio;
use App\Models\Selector;
use App\Models\Stop;
use Illuminate\Support\Facades\Redirect;

class StopController extends BaseController
{
    protected function setUpController(): void
    {
        parent::setUpController();
        $this->setModuleName('stops');
        $this->setSearchColumns(['title']);
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
                ApiRelation::make()
                    ->field('title')
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
                    ->hide()
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
                    ->name('objects')
                    ->label('Object')
                    ->modulesCustom([
                        [
                            'name' => 'loanObjects',
                            'label' => 'Loan Objects',
                        ],
                        [
                            'name' => 'collectionObjects',
                            'label' => 'Collection Objects',
                            'params' => ['artwork_id' => $stop->artwork_id],
                        ],
                    ])
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

    public function createWithObject()
    {
        $stop = Stop::create(['artwork_id' => $this->request->query('artwork_id')]);
        return Redirect::to(moduleRoute($this->moduleName, $this->routePrefix, 'edit', ['stop' => $stop->id]));
    }

    public function createWithSound()
    {
        $audio = Audio::find(request('sound_id'));
        $stop = Stop::create();
        $stop->selector()->save($audio->selector);
        return Redirect::to(moduleRoute($this->moduleName, $this->routePrefix, 'edit', ['stop' => $stop->id]));
    }
}
