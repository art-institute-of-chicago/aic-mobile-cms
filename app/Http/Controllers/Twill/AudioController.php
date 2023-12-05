<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Services\Forms\BladePartial;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Radios;
use A17\Twill\Services\Forms\Fields\Wysiwyg;
use A17\Twill\Services\Forms\Fieldset;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use App\Http\Controllers\Twill\Columns\ApiRelation;

class AudioController extends BaseApiController
{
    protected function setUpController(): void
    {
        parent::setUpController();
        $this->enableAugmentedModel();
        $this->enableTitleMarkup();
        $this->setDisplayName('Audio');
        $this->setModuleName('audio');
        $this->setSearchColumns(['title']);
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        return parent::additionalIndexTableColumns()
            ->add(
                ApiRelation::make()
                    ->field('number')
                    ->title('Selector Number')
                    ->relation('selector')
            )
            ->add(
                Text::make()
                    ->field('content')
                    ->title('Audio')
                    ->customRender(function ($audio) {
                        return view('admin.audio-controls', ['src' => $audio->content])->render();
                    })
                    ->optional()
            )
            ->add(
                Text::make()
                    ->field('locale')
                    ->title('Language')
                    ->optional()
                    ->hide()
            )
            ->add(
                Text::make()
                    ->field('transcript')
                    ->optional()
                    ->hide()
            );
    }

    protected function additionalBrowserTableColumns(): TableColumns
    {
        return parent::additionalBrowserTableColumns()
            ->add(
                Text::make()
                    ->field('locale')
                    ->title('Language')
            );
    }

    protected function additionalFormFields($audio, $apiSound): Form
    {
        return Form::make()
            ->add(
                Input::make()
                    ->name('selector_number')
                    ->type('number')
                    ->min(10)
                    ->max(999)
            )
            ->add(
                Radios::make()
                    ->name('locale')
                    ->label('Language')
                    ->options(
                        collect(getLocales())
                            ->mapWithKeys(fn ($language) => [$language => $language])
                            ->toArray()
                    )
                    ->default(config('app.locale'))
                    ->inline()
                    ->border()
            )
            ->add(
                Wysiwyg::make()
                    ->name('transcript')
                    ->type('textarea')
                    ->toolbarOptions(['bold', 'italic'])
                    ->allowSource()
            );
    }

    public function getSideFieldSets($audio): Form
    {
        return parent::getSideFieldSets($audio)
            ->addFieldset(
                Fieldset::make()
                    ->id('audio_player')
                    ->title('Audio Player')
                    ->fields([
                        BladePartial::make()
                            ->view('admin.fields.audio')
                            ->withAdditionalParams(['src' => $audio->getApiModel()->content]),
                    ])
            );
    }
}
