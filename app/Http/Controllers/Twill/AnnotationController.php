<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\BladePartial;
use A17\Twill\Services\Forms\Fields\Browser;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Medias;
use A17\Twill\Services\Forms\Fieldset;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;

class AnnotationController extends BaseController
{
    protected function setUpController(): void
    {
        $this->disableBulkPublish();
        $this->disablePermalink();
        $this->disablePublish();
        $this->enableShowImage();
        $this->setModuleName('annotations');
        $this->setSearchColumns(['label', 'description']);
    }

    public function edit(TwillModelContract|int $id): mixed
    {
        return parent::edit($id)->with(['editableTitle' => false]);
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        return parent::additionalIndexTableColumns()
            ->add(
                Text::make()
                    ->field('label')
                    ->optional()
                    ->hide()
            );
    }

    public function getForm(TwillModelContract $model): Form
    {
        $content = Form::make()
            ->merge($this->additionalFormFields($model));
        return Form::make()->addFieldset(
            Fieldset::make()
                ->title('Content')
                ->id('content')
                ->fields($content->toArray())
        );
    }

    public function getCreateForm(): Form
    {
        return Form::make()
            ->add(
                Input::make()
                    ->name('label')
                    ->translatable()
            );
    }

    public function additionalFormFields(TwillModelContract $object): Form
    {
        return parent::additionalFormFields($object)
            ->add(
                Input::make()
                    ->name('label')
                    ->translatable()
            )
            ->add(
                Input::make()
                    ->name('description')
                    ->translatable()
            )
            ->add(
                Medias::make()
                    ->name('upload')
                    ->label('Image')
            )
            ->add(
                Browser::make()
                    ->name('floors')
                    ->label('Floor')
                    ->modules([\App\Models\Floor::class])
            )
            ->add(
                Browser::make()
                    ->name('types')
                    ->modules([\App\Models\AnnotationType::class])
                    ->sortable(false)
                    ->max(\App\Models\AnnotationType::count())
            )
            ->add(
                BladePartial::make()
                    ->view('admin.fields.map')
                    ->withAdditionalParams([
                        'src' => route('twill.map.index', [
                            'latitude' => $object->latitude ?? '',
                            'longitude' => $object->longitude ?? '',
                            'floor' => $object->floor ?? '',
                        ]),
                    ])
            )
            ->add(
                Input::make()
                    ->name('latitude')
                    ->label('Latitude')
                    ->type('number')
            )
            ->add(
                Input::make()
                    ->name('longitude')
                    ->label('Longitude')
                    ->type('number')
            );
    }
}
