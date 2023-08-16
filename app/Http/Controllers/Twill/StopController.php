<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Select;
use A17\Twill\Services\Forms\Fieldset;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Forms\Option;
use A17\Twill\Services\Forms\Options;
use A17\Twill\Services\Listings\TableColumns;
use App\Http\Controllers\Twill\Columns\ApiRelation;
use Illuminate\Support\Str;

class StopController extends BaseModuleController
{
    protected $moduleName = 'stops';

    protected function setUpController(): void
    {
        $this->disablePermalink();
        $this->enableReorder();
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        $table = parent::additionalIndexTableColumns();

        $table->add(
            ApiRelation::make()
                ->field('artwork_id')
                ->title('Object')
                ->relation('object')
        );
        $table->add(
            ApiRelation::make()
                ->field('sound_id')
                ->title('Audio')
                ->relation('audio')
        );

        return $table;
    }

    public function getForm(TwillModelContract $stop): Form
    {
        $form = parent::getForm($stop);
        $form->add(
            Input::make()
                ->name('title')
        );
        $form->add(
            Input::make()
                ->name('artwork_id')
                ->note('Datahub Id')
        );
        $form->add(
            Input::make()
                ->name('sound_id')
                ->note('Datahub Id')
        );

        return $form;
    }

    public function getSideFieldSets(TwillModelContract $stop): Form
    {
        return parent::getSideFieldSets($stop)
            // For some reason, the side form will not render unless there is a
            // field in the default Content fieldset.
            ->add(
                Input::make()
                    ->name('title')
                    ->disabled()
            )
            ->addFieldset(
                Fieldset::make()
                    ->id('api_relations')
                    ->title('API Relations')
                    ->fields([
                        Select::make()
                            ->name('artwork_id')
                            ->label('Object')
                            ->placeholder('Select Object')
                            ->note('100 most recent')
                            ->options(
                                Options::make(
                                    $this->objectOptions()
                                )
                            ),
                        Select::make()
                            ->name('sound_id')
                            ->label('Audio')
                            ->placeholder('Select Audio')
                            ->note('100 most recent')
                            ->options(
                                Options::make(
                                    $this->audioOptions()
                                )
                            ),
                    ])
            );
    }

    private function objectOptions(): array
    {
        $options = [];
        $objects = \App\Models\Api\Artwork::query()
            ->limit(100)
            ->get()
            ->sortBy('source_updated_at');
        foreach ($objects as $object) {
            $options[] = Option::make($object->id, Str::words($object->title, 5));
        }
        return $options;
    }

    private function audioOptions(): array
    {
        $options = [];
        $audios = \App\Models\Api\Sound::query()
            ->limit(100)
            ->get()
            ->sortByDesc('source_updated_at');
        foreach ($audios as $audio) {
            $options[] = Option::make($audio->id, Str::words($audio->title, 5));
        }
        return $options;
    }
}
