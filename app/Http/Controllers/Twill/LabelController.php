<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Wysiwyg;
use A17\Twill\Services\Forms\Fieldset;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;

class LabelController extends BaseController
{
    protected function setUpController(): void
    {
        parent::setUpController();
        $this->disableCreate();
        $this->disableDelete();
        $this->disablePublish();
        $this->setModuleName('labels');
        $this->setSearchColumns(['key', 'text']);
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        return parent::additionalIndexTableColumns()
            ->add(
                Text::make()
                    ->field('text')
            );
    }

    public function getForm(TwillModelContract $model): Form
    {
        $content = Form::make()
            ->add(
                Input::make()
                    ->name($this->titleColumnKey)
                    ->disabled()
            )
            ->merge($this->additionalFormFields($model));
        return Form::make()->addFieldset(
            Fieldset::make()
                ->title('Content')
                ->id('content')
                ->fields($content->toArray())
        );
    }

    public function additionalFormFields(TwillModelContract $label): Form
    {
        return parent::additionalFormFields($label)
            ->add(
                Wysiwyg::make()
                    ->name('text')
                    ->type('textarea')
                    ->translatable()
                    ->toolbarOptions(['bold', 'italic'])
                    ->allowSource()
            );
    }
}
