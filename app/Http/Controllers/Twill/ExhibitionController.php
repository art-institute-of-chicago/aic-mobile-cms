<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Services\Forms\Fields\Checkbox;
use A17\Twill\Services\Forms\Fields\DatePicker;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Select;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Forms\Option;
use A17\Twill\Services\Forms\Options;
use A17\Twill\Services\Listings\Columns\Boolean;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\Filters\QuickFilter;
use A17\Twill\Services\Listings\Filters\QuickFilters;
use A17\Twill\Services\Listings\TableColumns;
use App\Models\Api\Exhibition;
use Illuminate\Contracts\Database\Query\Builder;

class ExhibitionController extends BaseApiController
{
    protected $moduleName = 'exhibitions';
    protected $hasAugmentedModel = true;

    public function setUpController(): void
    {
        parent::setUpController();

        $this->setSearchColumns(['title']);

        $this->enableFeature();
    }

    public function quickFilters(): QuickFilters
    {
        return $this->getDefaultQuickFilters()
            ->add(QuickFilter::make()
                ->queryString('sfnc')
                ->label('Started, Featured, and Not Closed')
                ->apply(fn(Builder $builder) => $builder->startedFeaturedAndNotClosed())
                ->amount(fn() => Exhibition::query()->startedFeaturedAndNotClosed()->count()));
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        return TableColumns::make()
            ->add(Boolean::make()
                ->field('is_featured'))
            ->add(Text::make()
                ->field('status'))
            ->add(Text::make()
                ->field('aic_start_at')
                ->title('Start At')
                ->optional())
            ->add(Text::make()
                ->field('aic_end_at')
                ->title('End At')
                ->optional())
            ->add(Text::make()
                ->field('image_url')
                ->optional()
                ->hide());
    }
    public function additionalFormFields($exhibition, $apiExhibition): Form
    {
        $apiValues = $apiExhibition->getAttributes();
        return Form::make()
            ->add(Checkbox::make()
                ->name('is_featured')
                ->default($apiValues['is_featured']))
            ->add(Select::make()
                ->name('status')
                ->placeholder('Select Status')
                ->options(Options::make([
                    Option::make('', 'Unset Status'),
                    Option::make('Closed', 'Closed'),
                    Option::make('Confirmed', 'Confirmed'),
                    Option::make('Traveling', 'Traveling')]))
                ->default($apiValues['status']))
            ->add(DatePicker::make()
                ->name('aic_start_at')
                ->label('Start At')
                ->default($apiValues['aic_start_at']))
            ->add(DatePicker::make()
                ->name('aic_end_at')
                ->label('End At')
                ->default($apiValues['aic_end_at']))
            ->add(Input::make()
                ->name('image_url')
                ->disabled()
                ->note('readonly'));
    }
}
