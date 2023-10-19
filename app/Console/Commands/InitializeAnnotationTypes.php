<?php

namespace App\Console\Commands;

use App\Models\AnnotationType;
use App\Models\Translations\AnnotationTypeTranslation;
use Illuminate\Console\Command;

class InitializeAnnotationTypes extends Command
{
    protected $signature = 'app:initialize-types';

    protected $description = 'Insert all annotation types into the database';

    public function handle()
    {
        foreach (AnnotationType::TITLES as $title) {
            if (!AnnotationTypeTranslation::firstWhere('title', $title)) {
                $type = AnnotationType::create();
                $type->setAttribute('title', $title);
                $type->setAttribute('active', true);
                $type->save();
            }
        }
    }
}
