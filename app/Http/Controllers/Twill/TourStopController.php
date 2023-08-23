<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Breadcrumbs\NestedBreadcrumbs;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\TableColumns;
use App\Http\Controllers\Twill\Columns\ApiRelation;

class TourStopController extends BaseController
{
    protected $modelName = 'TourStop';

    protected function setUpController(): void
    {
        parent::setUpController();
        $this->disablePublish();
        $this->enableReorder();
        $this->setModuleName('tours.stops');

        if (request('tour')) {
            $this->setBreadcrumbs(
                NestedBreadcrumbs::make()
                    ->forParent(
                        parentModule: 'tours',
                        module: 'tours.stops',
                        activeParentId: request('tour'),
                        repository: \App\Repositories\TourRepository::class
                    )
                    ->label('Stops')
            );
        }
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        return parent::additionalIndexTableColumns()
            ->add(
                ApiRelation::make()
                    ->field('artwork_id')
                    ->title('Object')
                    ->relation('object')
            )
            ->add(
                ApiRelation::make()
                    ->field('sound_id')
                    ->title('Audio')
                    ->relation('audio')
            );
    }

    public function additionalFormFields(TwillModelContract $stop): Form
    {
        return parent::additionalFormFields($stop)
            ->add(
                Input::make()
                    ->name('artwork_id')
                    ->label('Object Id')
                    ->required()
                    ->note('Datahub Id')
            )
            ->add(
                Input::make()
                    ->name('sound_id')
                    ->label('Audio Id')
                    ->translatable()
                    ->required()
                    ->note('Datahub Id')
            );
    }
}
