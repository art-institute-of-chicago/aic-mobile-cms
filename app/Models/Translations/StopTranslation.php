<?php

namespace App\Models\Translations;

use A17\Twill\Models\Model;
use App\Models\Behaviors\HasTitleMarkup;
use App\Models\Stop;

class StopTranslation extends Model
{
    use HasTitleMarkup;

    protected $baseModuleModel = Stop::class;
}
