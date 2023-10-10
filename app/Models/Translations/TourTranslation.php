<?php

namespace App\Models\Translations;

use A17\Twill\Models\Model;
use App\Models\Behaviors\HasTitleMarkup;
use App\Models\Tour;

class TourTranslation extends Model
{
    use HasTitleMarkup;

    protected $baseModuleModel = Tour::class;
}
