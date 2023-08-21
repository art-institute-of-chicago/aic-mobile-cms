<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Form;

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
                    ->customRender(function ($sound) {
                        return "<audio controls src='$sound->content'></audio>";
                    })
                    ->optional()
            );
    }

    protected function additionalFormFields($sound, $apiSound): Form
    {
        $apiValues = array_map(
            fn ($value) => $value ?? (string) $value,
            $apiSound->getAttributes()
        );
        return Form::make()
            ->add(
                Input::make()
                    ->name('content')
                    ->placeholder($apiValues['content'])
                    ->disabled()
                    ->note('readonly')
            )
            ->add(
                Input::make()
                    ->name('transcript')
                    ->type('textarea')
            );
    }
}
