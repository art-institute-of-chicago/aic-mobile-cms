<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController;
use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fieldset;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\FeaturedStatus;
use A17\Twill\Services\Listings\Columns\Image;
use A17\Twill\Services\Listings\Columns\Languages;
use A17\Twill\Services\Listings\Columns\PublishStatus;
use A17\Twill\Services\Listings\Columns\ScheduledStatus;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use App\Http\Controllers\Behaviors\HandlesTitleMarkup;

class BaseController extends ModuleController
{
    use HandlesTitleMarkup;

    protected function setUpController(): void
    {
        $this->disablePermalink();
        $this->enableSkipCreateModal();
    }

    protected function getIndexTableColumns(): TableColumns
    {
        $columns = TableColumns::make();
        if ($this->getIndexOption('publish')) {
            $columns->add(
                PublishStatus::make()
                    ->title(twillTrans('twill::lang.listing.columns.published'))
                    ->sortable()
                    ->optional()
            );
        }
        // Add default columns.
        if ($this->getIndexOption('showImage')) {
            $columns->add(
                Image::make()
                    ->field('thumbnail')
                    ->title(twillTrans('Image'))
            );
        }
        if ($this->getIndexOption('feature') && $this->repository->isFillable('featured')) {
            $columns->add(
                FeaturedStatus::make()
                    ->title(twillTrans('twill::lang.listing.columns.featured'))
            );
        }
        $columns->add(
            Text::make()
                ->field('updated_at')
                ->sortable()
                ->optional()
                ->hide()
        );
        $columns->add(
            $this->getTitleColumn()
                ->linkToEdit()
        );
        $columns = $columns->merge($this->additionalIndexTableColumns());
        if ($this->getIndexOption('includeScheduledInList') && $this->repository->isFillable('publish_start_date')) {
            $columns->add(
                ScheduledStatus::make()
                    ->title(twillTrans('twill::lang.listing.columns.published'))
                    ->optional()
            );
        }
        if ($this->moduleHas('translations') && count(getLocales()) > 1) {
            $columns->add(
                Languages::make()
                    ->title(twillTrans('twill::lang.listing.languages'))
                    ->optional()
            );
        }

        return $columns;
    }

    public function getForm(TwillModelContract $model): Form
    {
        $title = $this->getTitleField();
        if (classHasTrait($model::class, HasTranslation::class)) {
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
}
