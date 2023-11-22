<?php

namespace App\Console\Commands;

use App\Models\Floor;
use App\Models\Label;
use App\Models\LoanObject;
use App\Models\Selector;
use App\Models\Stop;
use App\Models\Tour;
use App\Models\Translations\FloorTranslation;
use App\Models\Translations\LabelTranslation;
use App\Models\Translations\TourTranslation;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MigrateData extends Command
{
    const APP_DATA_FILE = 'https://aic-mobile-tours.artic.edu/sites/default/files/appData-v3.json';

    protected $signature = 'app:migrate-data';

    protected $description = 'Migrate data from Drupal-generated appData-v3.json file';

    protected $appData = [];

    public function handle()
    {
        $this->appData = json_decode(file_get_contents(self::APP_DATA_FILE), associative: true);
        $this->migrateGeneralInfo();
        $this->migrateTours();
    }

    public function migrateGeneralInfo()
    {
        $data = $this->appData['general_info'];
        $nonLabels = ['title', 'status', 'nid', 'type', 'translations', 'language'];
        $translations = $data['translations'];
        $defaultTranslation = $data;
        $defaultTranslation['language'] = config('app.locale');
        $translations[] = $defaultTranslation;
        foreach ($translations as $labels) {
            $locale = $labels['language'];
            foreach ($labels as $key => $text) {
                if (in_array($key, $nonLabels)) {
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

    public function migrateTours()
    {
        $data = $this->appData['tours'];
        foreach ($data as $index => $datum) {
            $translations = $datum['translations'];
            $defaultTranslation = $datum;
            $defaultTranslation['language'] = config('app.locale');
            $translations[] = $defaultTranslation;
            $duration = Str::of($datum['tour_duration']);
            if ($duration->contains(':')) {
                $duration = $duration->before(':');
            } elseif ($duration->contains('min')) {
                $duration = $duration->before('min')->trim();
            } else {
                $duration = 0;
            }
            $tour = Tour::create([
                'duration' => $duration,
                'featured' => in_array($datum['nid'], $this->appData['dashboard']['featured_tours']),
                'position' => $index,
                'publish_start_date' => now(),
                'published' => true,
            ]);
            foreach ($translations as $translation) {
                $tourTranslation = TourTranslation::make([
                    'active' => true,
                    'description' => Str::of($translation['description'])->trim(),
                    'intro' => Str::of($translation['intro'])->trim(),
                    'locale' => $translation['language'],
                    'title' => Str::of($translation['title'])->trim(),
                    'tour_id' => $tour->id,
                ]);
                $tour->translation()->save($tourTranslation);
                if ($datum['selector_number']) {
                    $selector = Selector::firstOrCreate([
                        'number' => $datum['selector_number'],
                    ]);
                    $tour->selector()->save($selector);
                }
            }
            $this->migrateTourStops($tour, $datum['tour_stops']);
        }
    }

    public function migrateTourStops($tour, array $tourStops)
    {
        foreach ($tourStops as $index => $tourStop) {
            $object = $this->appData['objects'][$tourStop['object']] ?? null;
            if (is_null($object)) {
                continue;
            }
            if ($object['id']) {
                $objectId = $object['id'];
                $objectType = 'collectionObject';
            } else {
                $loanObject = LoanObject::create([
                    'artist_display' => $object['artist_culture_place_delim'],
                    'copyright_notice' => $object['copyright_notice'],
                    'credit_line' => $object['credit_line'],
                    'latitude' => $object['latitude'],
                    'longitude' => $object['longitude'],
                    'title' => $object['title'],
                ]);
                $objectId = $loanObject->id;
                $objectType = 'loanObject';
            }
            $stop = Stop::create([
                'active' => true,
                'object_id' => $objectId,
                'object_type' => $objectType,
                'publish_start_date' => now(),
                'published' => true,
                'title' => $object['title'],
            ]);
            $selector = Selector::firstOrCreate([
                'number' => $object['audio_commentary'][0]['object_selector_number'],
            ]);
            $stop->selector()->save($selector);
            $tour->stops()->attach($stop, ['position' => $index]);
        }
    }

}
