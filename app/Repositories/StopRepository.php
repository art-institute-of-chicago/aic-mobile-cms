<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleTranslations;
use A17\Twill\Repositories\Behaviors\HandleRevisions;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\Stop;

class StopRepository extends ModuleRepository
{
    use Behaviors\HandleApiBrowsers;
    use Behaviors\HandleApiRelations;
    use HandleRevisions;
    use HandleTranslations;

    public function __construct(Stop $stop)
    {
        $this->model = $stop;
    }

    public function getFormFields($stop): array
    {
        $fields = parent::getFormFields($stop);
        $fields['browsers']['tour_stops'] = $this->getFormFieldsForBrowser($stop, 'tours');
        $fields['browsers']['audios'] = $this->getFormFieldsForBrowserApi(
            $stop,
            relation: 'audios',
            apiModel: \App\Models\Api\Sound::class,
            moduleName: 'sounds',
        );
        return $fields;
    }

    public function afterSave($stop, array $fields): void
    {
        if (array_key_exists('browsers', $fields)) {
            $this->updateBrowserApiRelated($stop, $fields, 'audios');
            $tourIds = collect($fields['browsers']['tour_stops'])->values()->pluck('id');
            $stop->tours()->sync($tourIds);
        }
        parent::afterSave($stop, $fields);
    }
}
