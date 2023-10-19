<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Services\Forms\BladePartial;
use A17\Twill\Services\Forms\Fields\Browser;
use A17\Twill\Services\Forms\Fields\Checkbox;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Medias;
use A17\Twill\Services\Forms\Fieldset;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Boolean;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\Filters\QuickFilter;
use A17\Twill\Services\Listings\Filters\QuickFilters;
use A17\Twill\Services\Listings\TableColumns;
use App\Http\Controllers\Twill\Columns\ApiRelation;
use App\Models\Api\CollectionObject;
use Illuminate\Contracts\Database\Query\Builder;

class CollectionObjectController extends BaseApiController
{
    protected function setUpController(): void
    {
        parent::setUpController();
        $this->eagerLoadListingRelations(['gallery']);
        $this->enableAugmentedModel();
        $this->enableShowImage();
        $this->setDisplayName('Collection Object');
        $this->setModuleName('collectionObjects');
        $this->setSearchColumns(['title', 'artist_display', 'datahub_id', 'main_reference_number']);
    }

    public function quickFilters(): QuickFilters
    {
        return $this->getDefaultQuickFilters()
            ->add(QuickFilter::make()
                ->queryString('is_on_view')
                ->label('On View')
                ->apply(fn (Builder $builder) => $builder->onView())
                ->amount(fn () => CollectionObject::query()->onView()->count()));
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        return parent::additionalIndexTableColumns()
            ->add(Text::make()
                ->field('artist_display'))
            ->add(Boolean::make()
                ->field('is_on_view'))
            ->add(
                ApiRelation::make()
                    ->field('title')
                    ->title('Gallery')
                    ->relation('gallery')
            )
            ->add(Text::make()
                ->field('main_reference_number')
                ->optional()
                ->hide())
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
                ->customRender(function (CollectionObject $object) {
                    return $object->latitude ? number_format((float) $object->latitude, 13) : '';
                })
                ->optional()
                ->hide())
            ->add(Text::make()
                ->field('longitude')
                ->customRender(function (CollectionObject $object) {
                    return $object->longitude ? number_format((float) $object->longitude, 13) : '';
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

    protected function getBrowserData(array $scopes = []): array
    {
        if ($this->request->has('selector_id')) {
            $scopes['selector_id'] = $this->request->query('selector_id');
        }
        return parent::getBrowserData($scopes);
    }

    protected function getBrowserItems(array $scopes = [])
    {
        if (array_key_exists('selector_id', $scopes)) {
            $selector = \App\Models\Selector::find($scopes['selector_id']);
            $soundIds = $selector->audio->pluck('datahub_id');
            $results = CollectionObject::query()->bySoundIds($soundIds)->get();
            if ($results->isNotEmpty()) {
                return $results;
            }
        }
        return parent::getBrowserItems($scopes);
    }


    protected function additionalFormFields($object, $apiCollectionObject): Form
    {
        $apiValues = array_map(
            fn ($value) => $value ?? (string) $value,
            $apiCollectionObject->getAttributes()
        );
        $latitude = $object->latitude ?? $apiCollectionObject->latitude ?? '';
        $longitude = $object->longitude ?? $apiCollectionObject->longitude ?? '';
        $floor = $object->gallery?->floor ?? '';
        return Form::make()
            ->add(
                BladePartial::make()
                    ->view('admin.fields.image')
                    ->withAdditionalParams(['src' => $apiCollectionObject->imageFront('iiif')['src'] ?? ''])
            )
            ->add(
                Medias::make()
                    ->name('upload')
                    ->label('Override Image')
                    ->note('This will replace the image above')
            )
            ->add(
                Input::make()
                    ->name('artist_display')
                    ->placeholder($apiValues['artist_display'])
            )
            ->add(
                Checkbox::make()
                    ->name('is_on_view')
                    ->default($apiValues['is_on_view'])
            )
            ->add(
                Input::make()
                    ->name('main_reference_number')
                    ->placeholder($apiValues['main_reference_number'])
                    ->disabled()
                    ->note('readonly')
            )
            ->add(
                Input::make()
                    ->name('credit_line')
                    ->placeholder($apiValues['credit_line'])
            )
            ->add(
                Input::make()
                    ->name('copyright_notice')
                    ->placeholder($apiValues['copyright_notice'])
            )
            ->add(
                Browser::make()
                    ->name('gallery')
                    ->modules([\App\Models\Api\Gallery::class])
            )
            ->add(
                BladePartial::make()
                    ->view('admin.fields.map')
                    ->withAdditionalParams([
                        'src' => route('twill.map.index', [
                            'latitude' => $latitude,
                            'longitude' => $longitude,
                            'floor' => $floor
                        ]),
                    ])
            )
            ->add(
                Input::make()
                    ->name('latitude')
                    ->label('Latitude')
                    ->type('number')
                    ->placeholder($latitude)
            )
            ->add(
                Input::make()
                    ->name('longitude')
                    ->label('Longitude')
                    ->type('number')
                    ->placeholder($longitude)
            );
    }

    public function getSideFieldSets($object): Form
    {
        return parent::getSideFieldSets($object)
            ->addFieldset(
                Fieldset::make()
                    ->id('object_actions')
                    ->title('Actions')
                    ->fields([
                        BladePartial::make()
                            ->view('admin.fields.action')
                            ->withAdditionalParams([
                                'action' => 'Create Stop with Object',
                                'href' => route('twill.stops.createWithObject', parameters: [
                                    'artwork_id' => $object->datahub_id,
                                ]),
                            ])
                    ])
            );
    }
}
