<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Services\Forms\Fields\Checkbox;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Map;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use App\Models\Api\Gallery;

class GalleryController extends BaseApiController
{
    public function setUpController(): void
    {
        parent::setUpController();
        $this->disableIncludeScheduledInList();
        $this->enableAugmentedModel();
        $this->setDisplayName('Gallery');
        $this->setModuleName('galleries');
        $this->setSearchColumns(['title', 'floor', 'number']);
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        return parent::additionalIndexTableColumns()
            ->add(Text::make()
                ->field('floor')
                ->optional())
            ->add(Text::make()
                ->field('number')
                ->optional())
            ->add(Text::make()
                ->field('is_closed')
                ->title('Is Open')
                ->customRender(function (Gallery $gallery) {
                    // It's more comprehensible to have "closed" be a big red X
                    return $gallery->is_closed ? "❌" : "✅";
                })
                ->optional())
            ->add(Text::make()
                ->field('latitude')
                ->customRender(function (Gallery $gallery) {
                    return number_format((float) $gallery->latitude, 13);
                })
                ->optional()
                ->hide())
            ->add(Text::make()
                ->field('longitude')
                ->customRender(function (Gallery $gallery) {
                    return number_format((float) $gallery->longitude, 13);
                })
                ->optional()
                ->hide());
    }

    protected function additionalBrowserTableColumns(): TableColumns
    {
        return parent::additionalBrowserTableColumns()
            ->add(Text::make()
                ->field('floor')
            )
            ->add(Text::make()
                ->field('number')
            )
            ->add(Text::make()
                ->field('is_closed')
                ->title('Is Open')
                ->customRender(function (Gallery $gallery) {
                    // It's more comprehensible to have "closed" be a big red X
                    return $gallery->is_closed ? "❌" : "✅";
                })
            );
    }

    public function additionalFormFields($gallery, $apiGallery): Form
    {
        return Form::make([
            Input::make()
                ->name('floor')
                ->placeholder($apiGallery->floor),
            Input::make()
                ->name('number')
                ->placeholder($apiGallery?->number ?? ''),
            Checkbox::make()
                ->name('is_closed'),
            Map::make()
                ->name('latlng')
                ->label('Location (out of order)'),
            Input::make()
                ->name('latlng')
                ->label("Location's map data")
                ->type('textarea'),
            Input::make()
                ->name('latitude')
                ->placeholder($apiGallery->latitude),
            Input::make()
                ->name('longitude')
                ->placeholder($apiGallery->longitude),
        ]);
    }
}
