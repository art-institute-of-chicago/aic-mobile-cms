<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Services\Forms\BladePartial;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Radios;
use A17\Twill\Services\Forms\Fieldset;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;

class SoundController extends BaseApiController
{
    protected $moduleName = 'sounds';
    protected $hasAugmentedModel = true;

    protected function setUpController(): void
    {
        parent::setUpController();

        $this->setSearchColumns(['title']);
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        return parent::additionalIndexTableColumns()
            ->add(
                Text::make()
                    ->field('content')
                    ->title('Audio')
                    ->customRender(function ($sound) {
                        return view('admin.audio-controls', ['src' => $sound->content])->render();
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

    protected function additionalFormFields($sound, $apiSound): Form
    {
        return Form::make()
            ->add(
                Input::make()
                    ->name('content')
                    ->label('Url')
                    ->placeholder($apiSound->content)
                    ->disabled()
                    ->note('readonly')
            )
            ->add(
                Radios::make()
                    ->name('locale')
                    ->label('Language')
                    ->options(
                        collect(config('translatable.locales'))
                            ->mapWithKeys(fn ($language) => [$language => $language])
                            ->toArray()
                    )
                    ->default(config('app.locale'))
                    ->inline()
                    ->border()
            )
            ->add(
                Input::make()
                    ->name('transcript')
                    ->type('textarea')
            );
    }

    public function getSideFieldSets($sound): Form
    {
        return parent::getSideFieldSets($sound)
            ->addFieldset(
                Fieldset::make()
                    ->id('audio_player')
                    ->title('Audio Player')
                    ->fields([
                        BladePartial::make()
                            ->view('admin.fields.audio')
                            ->withAdditionalParams(['src' => $sound->getApiModel()->content]),
                    ])
            );
    }
}
