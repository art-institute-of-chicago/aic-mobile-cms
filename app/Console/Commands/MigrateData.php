<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Label;
use App\Models\Translations\LabelTranslation;

class MigrateData extends Command
{
    const APP_DATA_FILE = 'https://aic-mobile-tours.artic.edu/sites/default/files/appData-v3.json';

    protected $signature = 'app:migrate-data';

    protected $description = 'Migrate data from the Drupal-generated appData-v3.json file';

    public function handle()
    {
        $appData = json_decode(file_get_contents(self::APP_DATA_FILE), associative: true);
        $translations = $appData['general_info']['translations'];
        $defaultTranslation = $appData['general_info'];
        $defaultTranslation['language'] = config('app.locale');
        $translations[] = $defaultTranslation;
        foreach ($translations as $labels) {
            $locale = $labels['language'];
            foreach ($labels as $key => $text) {
                if (!in_array($key, Label::KEYS)) {
                    continue;
                }
                $label = Label::firstOrCreate(['key' => $key]);
                $translation = LabelTranslation::firstOrNew([
                    'active' => true,
                    'label_id' => $label->id,
                    'locale' => $locale,
                    'text' => $text,
                ]);
                $label->translations()->save($translation);
            }
        }
    }
}
