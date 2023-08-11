<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\Checkbox;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Map;
use A17\Twill\Services\Forms\Fields\Select;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Forms\Option;
use A17\Twill\Services\Forms\Options;
use A17\Twill\Services\Listings\Columns\Boolean;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\Filters\QuickFilter;
use A17\Twill\Services\Listings\Filters\QuickFilters;
use A17\Twill\Services\Listings\TableColumns;
use App\Http\Controllers\Twill\Columns\ApiRelation;
use App\Models\Api\Artwork;
use Illuminate\Contracts\Database\Query\Builder;

class ArtworkController extends BaseApiController
{
    protected $moduleName = 'artworks';
    protected $hasAugmentedModel = true;

    private $galleries = [];

    protected function setUpController(): void
    {
        parent::setUpController();

        $this->setSearchColumns(['title', 'artist_display', 'catalogue_display']);

        $this->eagerLoadListingRelations(['gallery']);
    }

    public function quickFilters(): QuickFilters
    {
        return $this->getDefaultQuickFilters()
            ->add(QuickFilter::make()
                ->queryString('is_on_view')
                ->label('On View')
                ->apply(fn (Builder $builder) => $builder->onView())
                ->amount(fn () => Artwork::query()->onView()->count()));
    }


    protected function additionalIndexTableColumns(): TableColumns
    {
        return parent::additionalIndexTableColumns()
            ->add(Text::make()
                ->field('artist_display'))
            ->add(Boolean::make()
                ->field('is_on_view'))
            ->add(ApiRelation::make()
                ->field('gallery_id')
                ->title('Gallery')
                ->relation('gallery'))
            ->add(Text::make()
                ->field('credit_line')
                ->optional()
                ->hide())
            ->add(Text::make()
                ->field('copyright_notice')
                ->optional()
                ->hide())
            ->add(Text::make()
                ->field('latitude')
                ->customRender(function (Artwork $artwork) {
                    return $artwork->latitude ? number_format((float) $artwork->latitude, 13) : '';
                })
                ->optional()
                ->hide())
            ->add(Text::make()
                ->field('longitude')
                ->customRender(function (Artwork $artwork) {
                    return $artwork->longitude ? number_format((float) $artwork->longitude, 13) : '';
                })
                ->optional()
                ->hide())
            ->add(Text::make()
                ->optional()
                ->field('image_id')
                ->hide())
            ->add(Text::make()
                ->optional()
                ->field('gallery_id')
                ->hide());
    }

    protected function additionalFormFields($artwork, $apiArtwork): Form
    {
        $apiValues = array_map(
            fn ($value) => $value ?? (string) $value,
            $apiArtwork->getAttributes()
        );
        return Form::make()
            ->add(Input::make()
                ->name('artist_display')
                ->placeholder($apiValues['artist_display']))
            ->add(Checkbox::make()
                ->name('is_on_view')
                ->default($apiValues['is_on_view']))
            ->add(Input::make()
                ->name('credit_line')
                ->placeholder($apiValues['credit_line']))
            ->add(Input::make()
                ->name('copyright_notice')
                ->placeholder($apiValues['copyright_notice']))
            ->add(Map::make()
                ->name('latlng')
                ->label('Location (out of order)'))
            ->add(Input::make()
                ->name('latlng')
                ->label("Location's map data")
                ->type('textarea'))
            ->add(Input::make()
                ->name('latitude')
                ->placeholder($apiValues['latitude']))
            ->add(Input::make()
                ->name('longitude')
                ->placeholder($apiValues['longitude']))
            ->add(Input::make()
                ->name('image_id')
                ->placeholder($apiValues['image_id'])
                ->disabled()
                ->note('readonly'))
            ->add(Input::make()
                ->name('gallery_id')
                ->placeholder($apiValues['gallery_id'])
                ->disabled()
                ->note('readonly'));
    }

    public function getSideFieldSets(TwillModelContract $artwork): Form
    {
        $artwork->refresh();
        $apiValues = $artwork->getApiModel()->getAttributes();
        return Form::make()
            ->add(Select::make()
                ->name('gallery_id')
                ->label('Gallery')
                ->placeholder('Select Gallery')
                ->options(
                    Options::make(
                        [Option::make('', 'Unset Gallery')] +
                        $this->galleryOptions()
                    )
                )
                ->default($apiValues['gallery_id']));
    }

    private function galleryOptions(): array
    {
        $options = [];
        if (!$this->galleries) {
            $this->galleries = \App\Models\Api\Gallery::query()
                ->orderBy('title')
                ->limit(100)
                ->get()
                ->sortBy([
                    ['floor', 'asc'],
                    ['number', 'asc'],
                    ['title', 'asc'],
                ]);
        }
        foreach ($this->galleries as $gallery) {
            $options[] = Option::make($gallery->id, $gallery);
        }
        return $options;
    }
}
