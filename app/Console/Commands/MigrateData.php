<?php

namespace App\Console\Commands;

use App\Models\Annotation;
use App\Models\AnnotationCategory;
use App\Models\AnnotationType;
use App\Models\ApiRelatable;
use App\Models\ApiRelation;
use App\Models\Api\CollectionObject as ApiCollectionObject;
use App\Models\Api\Gallery as ApiGallery;
use App\Models\Audio;
use App\Models\CollectionObject;
use App\Models\Floor;
use App\Models\Gallery;
use App\Models\Label;
use App\Models\LoanObject;
use App\Models\Revisions\TourRevision;
use App\Models\Selector;
use App\Models\Stop;
use App\Models\Tour;
use App\Models\Translations\AnnotationCategoryTranslation;
use App\Models\Translations\AnnotationTranslation;
use App\Models\Translations\AnnotationTypeTranslation;
use App\Models\Translations\FloorTranslation;
use App\Models\Translations\LabelTranslation;
use App\Models\Translations\TourTranslation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class MigrateData extends Command
{
    public const APP_DATA_FILE = 'https://aic-mobile-tours.artic.edu/sites/default/files/appData-v3.json';

    protected $signature = 'app:migrate-data';

    protected $description = 'Migrate data from Drupal-generated appData-v3.json file';

    protected $appData = [];

    protected $annotationProgress;

    protected $labelProgress;

    protected $tourProgress;

    public function handle()
    {
        $this->appData = json_decode(file_get_contents(self::APP_DATA_FILE), associative: true);
        $this->migrateMapAnnotations();
        $this->migrateGeneralInfo();
        $this->migrateTours();
    }

    public function migrateGeneralInfo()
    {
        $this->info('Labels');
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
        $translations = collect($translations);
        $this->labelProgress = $this->output->createProgressBar($translations->flatten()->count());
        $this->labelProgress->setFormat('verbose');
        $this->labelProgress->start();
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
            $this->labelProgress->advance();
        }
        $this->labelProgress->finish();
        $this->newLine();
    }

    public function migrateTours()
    {
        $this->info('Tours & Stops');
        if (Schema::disableForeignKeyConstraints()) {
            Selector::truncate();
            Tour::truncate();
            TourTranslation::truncate();
            TourRevision::truncate();
            DB::table('tour_stops')->truncate();
            Stop::truncate();
            CollectionObject::truncate();
            LoanObject::truncate();
            Audio::truncate();
            ApiRelatable::truncate();
            ApiRelation::truncate();
            Schema::enableForeignKeyConstraints();
        };
        $data = collect($this->appData['tours']);
        $this->tourProgress = $this->output->createProgressBar($data->flatten()->count());
        $this->tourProgress->setFormat('verbose');
        $this->tourProgress->start();
        foreach ($data as $index => $datum) {
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
            $number = $datum['selector_number'];
            if ($number && !$this->isKnownDuplicate($number, $datum['title'])) {
                $selector = Selector::firstOrCreate(['number' => (integer) $number]);
            } else {
                $selector = Selector::create();
            }
            $tour->selector()->save($selector);
            $translations = $datum['translations'];
            $defaultTranslation = $datum;
            $defaultTranslation['language'] = config('app.locale');
            $translations[] = $defaultTranslation;
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
            $tour->save();
            $this->migrateTourStops($tour, $datum['tour_stops']);
        }
        $this->tourProgress->finish();
        $this->newLine();
    }

    public function migrateTourStops($tour, array $tourStops)
    {
        foreach ($tourStops as $index => $tourStop) {
            $objectData = $this->appData['objects'][$tourStop['object']] ?? null;
            if (is_null($objectData)) {
                continue;
            }
            if ($gallery = ApiGallery::search($objectData['gallery_location'])->limit(1)->get()->first()) {
                Gallery::firstOrCreate(['datahub_id' => $gallery?->id]);
            }
            if ($objectData['id']) {
                CollectionObject::firstOrCreate(['datahub_id' => $objectData['id']]);
                $object = new ApiCollectionObject(['id' => $objectData['id']]);
            } else {
                $object = LoanObject::create([
                    'artist_display' => $objectData['artist_culture_place_delim'],
                    'copyright_notice' => $objectData['copyright_notice'],
                    'credit_line' => $objectData['credit_line'],
                    'latitude' => $objectData['latitude'],
                    'longitude' => $objectData['longitude'],
                    'title' => $objectData['title'],
                    'gallery_id' => $gallery?->id,
                ]);
            }
            $selectorData = collect($objectData['audio_commentary'])->firstWhere('audio', $tourStop['audio_id']);
            $selector = Selector::firstOrCreate(['number' => (integer) $selectorData['object_selector_number']]);
            $selector->fill([
                'object_id' => $object->id,
                'object_type' => Str::of(class_basename($object))->lcfirst(),
            ]);
            $selector->save();
            $stop = Stop::create([
                'active' => true,
                'publish_start_date' => now(),
                'published' => true,
                'title' => Str::of($objectData['title'])->trim(),
            ]);
            $stop->selector()->save($selector);
            $tour->stops()->attach($stop, ['position' => $index]);
            $stop->save();
            $this->tourProgress->advance();
        }
    }

    public function migrateMapAnnotations()
    {
        $this->info('Annotations');
        if (Schema::disableForeignKeyConstraints()) {
            Floor::truncate();
            FloorTranslation::truncate();
            Annotation::truncate();
            AnnotationTranslation::truncate();
            AnnotationType::truncate();
            AnnotationTypeTranslation::truncate();
            DB::table('annotation_annotation_type')->truncate();
            AnnotationCategory::truncate();
            AnnotationCategoryTranslation::truncate();
            Schema::enableForeignKeyConstraints();
        };
        $mapAnnotations = collect($this->appData['map_annontations']);
        $this->annotationProgress = $this->output->createProgressBar($mapAnnotations->count());
        $this->annotationProgress->setFormat('verbose');
        $this->annotationProgress->start();
        foreach ($mapAnnotations as $id => $mapAnnotation) {
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
            $this->annotationProgress->advance();
        }
        $this->annotationProgress->finish();
        $this->newLine();
    }

    /**
     * A couple of the verbal description tours have the same selector number as
     * the regular version.
     */
    private function isKnownDuplicate($number, $title)
    {
        $knownDuplicateNumbers = ['388', '639'];
        $title = Str::of($title);
        return in_array($number, $knownDuplicateNumbers) && $title->contains('Verbal Description');
    }
}
