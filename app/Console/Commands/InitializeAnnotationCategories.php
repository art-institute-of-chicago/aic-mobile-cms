<?php

namespace App\Console\Commands;

use App\Models\AnnotationCategory;
use App\Models\Translations\AnnotationCategoryTranslation;
use Illuminate\Console\Command;

class InitializeAnnotationCategories extends Command
{
    protected $signature = 'app:initialize-categories';

    protected $description = 'Insert all annotation categories into the database';

    public function handle()
    {
        foreach (AnnotationCategory::TITLES as $title) {
            ;
            if (!AnnotationCategoryTranslation::firstWhere('title', $title)) {
                $category = AnnotationCategory::create();
                $category->setAttribute('title', $title);
                $category->setAttribute('active', true);
                $category->save();
            }
        }
    }
}
