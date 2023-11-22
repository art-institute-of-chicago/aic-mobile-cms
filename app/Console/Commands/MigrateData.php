<?php

namespace App\Console\Commands;

use App\Models\Annotation;
use App\Models\AnnotationCategory;
use App\Models\AnnotationType;
use App\Models\Floor;
use App\Models\Label;
use App\Models\LoanObject;
use App\Models\Selector;
use App\Models\Stop;
use App\Models\Tour;
use App\Models\Translations\AnnotationCategoryTranslation;
use App\Models\Translations\AnnotationTranslation;
use App\Models\Translations\AnnotationTypeTranslation;
use App\Models\Translations\FloorTranslation;
use App\Models\Translations\LabelTranslation;
use App\Models\Translations\StopTranslation;
use App\Models\Translations\TourTranslation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
        $this->migrateMapAnnotations();
    }

    public function migrateGeneralInfo()
    {
        if (Schema::disableForeignKeyConstraints()) {
            Label::truncate();
            LabelTranslation::truncate();
            Schema::enableForeignKeyConstraints();
        };
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
                $translation = LabelTranslation::make([
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
        if (Schema::disableForeignKeyConstraints()) {
            Selector::truncate();
            Tour::truncate();
            TourTranslation::truncate();
            DB::table('tour_stops')->truncate();
            Stop::truncate();
            StopTranslation::truncate();
            LoanObject::truncate();
            Schema::enableForeignKeyConstraints();
        };
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
            if ($datum['selector_number']) {
                $selector = Selector::create(['number' => $datum['selector_number']]);
                $tour->selector()->save($selector);
            }
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
            $selector = Selector::create(['number' => $object['audio_commentary'][0]['object_selector_number']]);
            $stop->selector()->save($selector);
            $tour->stops()->attach($stop, ['position' => $index]);
        }
    }

    public function migrateMapAnnotations()
    {
        if (Schema::disableForeignKeyConstraints()) {
            Floor::truncate();
            FloorTranslation::truncate();
            Annotation::truncate();
            AnnotationTranslation::truncate();
            AnnotationType::truncate();
            AnnotationTypeTranslation::truncate();
            AnnotationCategory::truncate();
            AnnotationCategoryTranslation::truncate();
            Schema::enableForeignKeyConstraints();
        };
        foreach ($this->appData['map_annontations'] as $mapAnnotation) {
            $level = $mapAnnotation['floor'] == '0' ? 'LL' : $mapAnnotation['floor'];
            if ($level) {
                $floor = Floor::firstOrCreate([
                    'geo_id' => Floor::LEVELS[$level],
                    'level' => $level,
                ]);
                $floorTranslation = FloorTranslation::firstOrNew([
                    'active' => true,
                    'floor_id' => $floor->id,
                    'locale' => config('app.locale'),
                    'title' => "Floor $level",
                ]);
                $floor->translations()->save($floorTranslation);
            }
            switch ($mapAnnotation['annotation_type']) {
                case 'Amenity':
                    $categoryTitle = $mapAnnotation['annotation_type'];
                    $typeTitle = $mapAnnotation['amenity_type'];
                    break;
                case 'Department':
                    $categoryTitle = $mapAnnotation['annotation_type'];
                    $typeTitle = $mapAnnotation['annotation_type'];
                    break;
                case 'Text':
                    $categoryTitle = 'Area';
                    $typeTitle = $mapAnnotation['text_type'];
                    break;
                case 'Image':
                default:
                    continue 2;
            }
            $annotationCategoryTranslation = AnnotationCategoryTranslation::firstOrNew([
                'active' => true,
                'locale' => config('app.locale'),
                'title' => $categoryTitle,
            ]);
            if ($annotationCategoryId = $annotationCategoryTranslation->annotation_category_id) {
                $annotationCategory = AnnotationCategory::find($annotationCategoryId);
            } else {
                $annotationCategory = AnnotationCategory::create();
                $annotationCategory->translations()->save($annotationCategoryTranslation);
            }
            $annotationTypeTranslation = AnnotationTypeTranslation::firstOrNew([
                'active' => true,
                'locale' => config('app.locale'),
                'title' => $typeTitle,
            ]);
            if ($annotationTypeId = $annotationTypeTranslation->annotation_type_id) {
                $annotationType = AnnotationType::find($annotationTypeId);
            } else {
                $annotationType = AnnotationType::make();
                $annotationType->category()->associate($annotationCategory);
                $annotationType->save();
                $annotationType->translations()->save($annotationTypeTranslation);
            }
            $annotation = Annotation::create([
                'active' => true,
                'description' => $mapAnnotation['description'],
                'label' => $mapAnnotation['label'],
                'latitude' => $mapAnnotation['latitude'],
                'longitude' => $mapAnnotation['longitude'],
                'locale' => config('app.locale'),
            ]);
            $annotation->floor()->associate($floor);
            $annotation->types()->attach($annotationType);
            $annotation->save();
        }
    }
}
