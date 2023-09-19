<?php

namespace App\Http\Controllers\Twill;

use Illuminate\Support\Facades\Session;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\BladePartial;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Radios;
use A17\Twill\Services\Forms\Fieldset;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;
use App\Http\Controllers\Twill\Columns\ApiRelation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class SoundController extends BaseApiController
{
    protected function setUpController(): void
    {
        parent::setUpController();
        $this->enableAugmentedModel();
        $this->setDisplayName('Audio');
        $this->setModuleName('sounds');
        $this->setSearchColumns(['title']);
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        return parent::additionalIndexTableColumns()
            ->add(
                ApiRelation::make()
                    ->field('number')
                    ->title('Selector Number')
                    ->relation('selector')
            )
            ->add(
                Text::make()
                    ->field('content')
                    ->title('Audio')
                    ->customRender(function ($sound) {
                        return view('admin.audio-controls', ['src' => $sound->content])->render();
                    })
                    ->optional()
            )
            ->add(
                Text::make()
                    ->field('locale')
                    ->title('Language')
                    ->optional()
                    ->hide()
            )
            ->add(
                Text::make()
                    ->field('transcript')
                    ->optional()
                    ->hide()
            );
    }

    protected function additionalBrowserTableColumns(): TableColumns
    {
        return parent::additionalBrowserTableColumns()
            ->add(
                Text::make()
                    ->field('locale')
                    ->title('Language')
            );
    }

    protected function additionalFormFields($sound, $apiSound): Form
    {
        return Form::make()
            ->add(
                Input::make()
                    ->name('selector_number')
                    ->type('number')
                    ->min(10)
                    ->max(999)
            )
            ->add(
                Radios::make()
                    ->name('locale')
                    ->label('Language')
                    ->options(
                        collect(getLocales())
                            ->mapWithKeys(fn ($language) => [$language => $language])
                            ->toArray()
                    )
                    ->default(config('app.locale'))
                    ->inline()
                    ->border()
            )
            ->add(
                Input::make()
                    ->name('transcript')
                    ->type('textarea')
            );
    }

    public function getSideFieldSets($sound): Form
    {
        return parent::getSideFieldSets($sound)
            ->addFieldset(
                Fieldset::make()
                    ->id('audio_player')
                    ->title('Audio Player')
                    ->fields([
                        BladePartial::make()
                            ->view('admin.fields.audio')
                            ->withAdditionalParams(['src' => $sound->getApiModel()->content]),
                    ])
            )
            ->addFieldset(
                Fieldset::make()
                    ->id('audio_actions')
                    ->title('Actions')
                    ->fields($this->actions($sound))
            );
    }

    /**
     * If the update is successful, respond with a self-redirect to induce a
     * page layout refresh.
     */
    public function update(int|TwillModelContract $id, ?int $submoduleId = null): JsonResponse
    {
        $response = parent::update($id, $submoduleId);
        if ($response->getStatusCode() == 200) {
            Session::put($this->moduleName . '_retain', false);
            return $this->respondWithRedirect(
                moduleRoute($this->moduleName, $this->routePrefix, 'edit', [Str::singular($this->moduleName) => $id])
            );
        }
        return $response;
    }

    protected function actions($sound): array
    {
        if ($sound->selector && !$sound->selector->selectable) {
            return [
                BladePartial::make()
                    ->view('admin.fields.action')
                    ->withAdditionalParams([
                        'action' => 'Create Stop with Audio',
                        'href' => route('twill.stops.create-with-sound', parameters: [
                            'sound_id' => $sound->id,
                        ]),
                    ]),
                BladePartial::make()
                    ->view('admin.fields.action')
                    ->withAdditionalParams([
                        'action' => 'Create Tour with Intro Audio',
                        'href' => route('twill.tours.create-with-sound', parameters: [
                            'sound_id' => $sound->id,
                        ]),
                    ]),
            ];
        }
        return [];
    }
}
