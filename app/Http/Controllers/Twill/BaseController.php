<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController;
use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fieldset;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;

class BaseController extends ModuleController
{
    protected function setUpController(): void
    {
        $this->disablePermalink();
        $this->enableSkipCreateModal();
    }

    protected function getIndexTableColumns(): TableColumns
    {
        $columns = parent::getIndexTableColumns();
        $after = $columns->splice(1);
        $columns->push(
            Text::make()
                ->field('updated_at')
                ->sortable()
                ->optional()
                ->hide()
        );
        return $columns->merge($after);
    }

    public function getForm(TwillModelContract $model): Form
    {
        $title = Input::make()
            ->name('title')
            ->required();
        if ($this->isTranslatable($model)) {
            $title->translatable();
        }
        $content = Form::make()
            ->add($title)
            ->merge($this->additionalFormFields($model));
        return parent::getForm($model)->addFieldset(
            Fieldset::make()
                ->title('Content')
                ->id('content')
                ->fields($content->toArray())
        );
    }

    protected function additionalFormFields(TwillModelContract $model): Form
    {
        return new Form();
    }

    public function getSideFieldSets(TwillModelContract $model): Form
    {
        return parent::getSideFieldSets($model)
            // For some reason, the side form will not render unless there is a
            // field in the default Content fieldset. 🤷
            ->add(
                Input::make()
                    ->name('id')
                    ->disabled()
                    ->note('readonly')
            )
            ->addFieldset(
                Fieldset::make()
                    ->id('timestamps')
                    ->title('Timestamps')
                    ->closed()
                    ->fields([
                        Input::make()
                            ->name('created_at')
                            ->disabled()
                            ->note('readonly'),
                        Input::make()
                            ->name('updated_at')
                            ->disabled()
                            ->note('readonly'),
                    ])
            );
    }

    private function isTranslatable(TwillModelContract $class): bool
    {
        return in_array(HasTranslation::class, class_uses_recursive($class));
    }
}
