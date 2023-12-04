<?php

use App\Repositories\AnnotationRepository;
use App\Repositories\Api\CollectionObjectRepository;
use App\Repositories\Api\GalleryRepository;
use App\Repositories\FloorRepository;
use App\Repositories\LabelRepository;
use App\Repositories\SelectorRepository;
use App\Repositories\Serializers\AnnotationSerializer;
use App\Repositories\Serializers\AudioSerializer;
use App\Repositories\Serializers\DashboardSerializer;
use App\Repositories\Serializers\FloorSerializer;
use App\Repositories\Serializers\GallerySerializer;
use App\Repositories\Serializers\GeneralInfoSerializer;
use App\Repositories\Serializers\ObjectSerializer;
use App\Repositories\Serializers\SearchSerializer;
use App\Repositories\Serializers\TourSerializer;
use App\Repositories\StopRepository;
use App\Repositories\TourRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/audio_files', function () {
    $publishedStops = App::Make(StopRepository::class)
        ->getBaseModel()
        ->newQuery()
        ->visible()
        ->published()
        ->select(DB::raw(1))
        ->whereColumn('stops.id', 'selectors.selectable_id')
        ->where('selectors.selectable_type', 'stop');
    $selectorRepository = App::make(SelectorRepository::class);
    $audios = $selectorRepository
        ->getBaseModel()
        ->newQuery()
        ->whereExists($publishedStops)
        ->get()
        ->map(fn ($selector) => $selector->audios->isEmpty() ? null : $selector->audios)
        ->filter();
    $serializer = new AudioSerializer();
    return $serializer->serialize($audios);
});

Route::get('/dashboard', function () {
    $tourRepository = App::make(TourRepository::class);
    $featuredTours = $tourRepository->getBaseModel()->newQuery()->visible()->published()->featured()->get();
    $dashboardSerializer = new DashboardSerializer();
    return $dashboardSerializer->serialize($featuredTours);
});

Route::get('/data', function () {
    return ['data' => config('uris')];
});

Route::get('/exhibitions', function () {
    return ['exhibitions' => []];  // Legacy from Drupal
});

Route::get('/galleries', function () {
    $repository = App::make(GalleryRepository::class);
    $galleries = $repository->getBaseModel()->newQuery()->get();
    $serializer = new GallerySerializer();
    return $serializer->serialize($galleries);
});

Route::get('/general_info', function () {
    $repository = App::make(LabelRepository::class);
    $labels = $repository->getBaseModel()->newQuery()->get();
    $serializer = new GeneralInfoSerializer();
    return $serializer->serialize($labels);
});

Route::get('/map_annotations', function () {
    $repository = App::make(AnnotationRepository::class);
    $annotations = $repository
        ->getBaseModel()
        ->newQuery()
        ->distinctByType()
        ->get()
        ->map(function ($annotation) {
            return $annotation->load(['types' => function ($query) use ($annotation) {
                $query->where('id', $annotation->annotation_type_id);
            }]);
        });
    $serializer = new AnnotationSerializer();
    return $serializer->serialize($annotations);
});

Route::get('/map_floors', function () {
    $repository = App::make(FloorRepository::class);
    $floors = $repository->getBaseModel()->newQuery()->get();
    $serializer = new FloorSerializer();
    return $serializer->serialize($floors);
});

Route::get('/messages', function () {
    return ['messages' => []];  // Legacy from Drupal
});

Route::get('/objects', function () {
    $publishedStops = App::Make(StopRepository::class)
        ->getBaseModel()
        ->newQuery()
        ->visible()
        ->published()
        ->select(DB::raw(1))
        ->whereColumn('stops.id', 'selectors.selectable_id')
        ->where('selectors.selectable_type', 'stop');
    $selectorRepository = App::make(SelectorRepository::class);
    $loanObjects = $selectorRepository
        ->getBaseModel()
        ->newQuery()
        ->with(['object']) // This can only preload the LoanObjects
        ->whereExists($publishedStops)
        ->get()
        ->map(fn ($selector) => $selector->object)
        ->filter();
    // Since api models cannot be preloaded, bulk retrieve CollectionObjects
    $collectionObjectIds = $selectorRepository
        ->getBaseModel()
        ->newQuery()
        ->whereExists($publishedStops)
        ->where('object_type', 'collectionObject')
        ->get()
        ->pluck('object_id')
        ->unique()
        ->chunk(100); // The api only allows retrieving 100 records at a time
    $collectionObjectRepository = App::make(CollectionObjectRepository::class);
    $collectionObjects = $collectionObjectIds->reduce(function ($objects, $chunk) use ($collectionObjectRepository) {
        return $objects->concat(
            $collectionObjectRepository
                ->getBaseModel()
                ->newQuery()
                ->with(['gallery'])
                ->whereIn('id', $chunk->toArray())
                ->where('is_on_view', true)
                ->get()
        );
    }, collect());
    $allObjects = collect()->concat($collectionObjects)->concat($loanObjects);
    $serializer = new ObjectSerializer();
    return $serializer->serialize($allObjects);
});

Route::get('/search', function () {
    $repository = App::make(CollectionObjectRepository::class);
    $suggestedObjects = $repository->getBaseModel()->newQuery()->mostViewed()->get();
    $serializer = new SearchSerializer();
    return $serializer->serialize($suggestedObjects);
});

Route::get('/tour_categories', function () {
    return ['tour_categories' => []];  // Legacy from Drupal
});

Route::get('/tours', function () {
    $repository = App::make(TourRepository::class);
    $tours = $repository->getBaseModel()->newQuery()->visible()->published()->get();
    $serializer = new TourSerializer();
    return $serializer->serialize($tours);
});
