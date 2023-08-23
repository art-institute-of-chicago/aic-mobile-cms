<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Medias;
use A17\Twill\Services\Forms\Fields\Select;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Forms\Option;
use A17\Twill\Services\Forms\Options;
use A17\Twill\Services\Listings\Columns\NestedData;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use App\Http\Controllers\Twill\Columns\ApiRelation;
use Illuminate\Support\Str;

class TourController extends BaseController
{
    protected $moduleName = 'tours';

    private $galleries = [];

    protected function setUpController(): void
    {
        parent::setUpController();
        $this->enableReorder();
        $this->setModelName('Tour');
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
                Text::make()
                    ->field('duration_in_minutes')
                    ->title('Duration')
                    ->optional()
            )
            ->add(
                NestedData::make()
                    ->field('stops')
                    ->title('Stops')
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
                Select::make()
                    ->name('gallery_id')
                    ->label('Gallery')
                    ->placeholder('Select Gallery')
                    ->options(
                        Options::make(
                            [Option::make('', 'Unset Gallery')] +
                            $this->galleryOptions()
                        )
                    )
            )
            ->add(
                Input::make()
                    ->name('gallery_id')
            )
            ->add(
                Input::make()
                    ->name('selector_number')
                    ->type('number')
                    ->required()
            )
            // ->add(
            //     Select::make()
            //         ->name('sound_id')
            //         ->label('Audio')
            //         ->translatable()
            //         ->placeholder('Select Audio')
            //         ->note('100 most recent')
            //         ->options(
            //             Options::make(
            //                 $this->audioOptions()
            //             )
            //         ),
            // )
            ->add(
                Input::make()
                    ->name('sound_id')
                    ->label('Audio Id')
                    ->translatable()
                    ->required()
                    ->note('Datahub Sound Id')
            )
            ->add(
                Input::make()
                    ->name('duration')
                    ->type('number')
                    ->min(1)
                    ->default(1)
                    ->required()
                    ->note('in minutes')
            );
    }

    private function galleryOptions(): array
    {
        $options = [];
        if (!$this->galleries) {
            $this->galleries = \App\Models\Api\Gallery::query()
                ->orderBy('title')
                ->limit(100)
                ->get()
                ->sortBy([
                    ['floor', 'asc'],
                    ['number', 'asc'],
                    ['title', 'asc'],
                ]);
        }
        foreach ($this->galleries as $gallery) {
            $options[] = Option::make($gallery->id, $gallery);
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
