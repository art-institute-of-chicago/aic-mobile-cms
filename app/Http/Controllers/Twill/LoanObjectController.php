<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\Browser;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Map;
use A17\Twill\Services\Forms\Fields\Medias;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use App\Http\Controllers\Twill\Columns\ApiRelation;
use App\Models\LoanObject;

class LoanObjectController extends BaseController
{
    protected function setUpController(): void
    {
        parent::setUpController();
        $this->disablePublish();
        $this->enableShowImage();
        $this->setModuleName('loanObjects');
        $this->setSearchColumns(['main_reference_number', 'title', 'artist_display']);
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        return parent::additionalIndexTableColumns()
            ->add(
                Text::make()
                    ->field('main_reference_number')
            )
            ->add(
                Text::make()
                    ->field('artist_display')
            )
            ->add(
                ApiRelation::make()
                    ->field('title')
                    ->title('Gallery')
                    ->relation('gallery')
            )
            ->add(
                Text::make()
                    ->field('credit_line')
                    ->optional()
                    ->hide()
            )
            ->add(
                Text::make()
                    ->field('copyright_notice')
                    ->optional()
                    ->hide()
            )
            ->add(
                Text::make()
                    ->field('latitude')
                    ->customRender(function (LoanObject $object) {
                        return $object->latitude ? number_format((float) $object->latitude, 13) : '';
                    })
                    ->optional()
                    ->hide()
            )
            ->add(
                Text::make()
                    ->field('longitude')
                    ->customRender(function (LoanObject $object) {
                        return $object->longitude ? number_format((float) $object->longitude, 13) : '';
                    })
                    ->optional()
                    ->hide()
            );
    }

    public function additionalFormFields(TwillModelContract $object): Form
    {
        return parent::additionalFormFields($object)
            ->add(
                Medias::make()
                    ->name('upload')
                    ->label('Image')
            )
            ->add(
                Input::make()
                    ->name('artist_display')
            )
            ->add(
                Input::make()
                    ->name('main_reference_number')
            )
            ->add(
                Input::make()
                    ->name('credit_line')
            )
            ->add(
                Input::make()
                    ->name('copyright_notice')
            )
            ->add(
                Browser::make()
                    ->name('gallery')
                    ->modules([\App\Models\Api\Gallery::class])
            )
            ->add(
                Map::make()
                    ->name('latlng')
                    ->label('Location')
                    ->note('Coming Soon!')
            );
    }
}
