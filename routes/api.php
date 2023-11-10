<?php

use App\Repositories\Api\CollectionObjectRepository;
use App\Repositories\Api\GalleryRepository;
use App\Repositories\Api\SoundRepository;
use App\Repositories\Serializers\AudioSerializer;
use App\Repositories\Serializers\DashboardSerializer;
use App\Repositories\Serializers\GallerySerializer;
use App\Repositories\Serializers\ObjectSerializer;
use App\Repositories\Serializers\TourSerializer;
use App\Repositories\TourRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
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
    $repository = App::make(SoundRepository::class);
    $audios = $repository->getBaseModel()->newQuery()->get();
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

Route::get('/messages', function () {
    return ['messages' => []];  // Legacy from Drupal
});

Route::get('/objects', function () {
    $repository = App::make(CollectionObjectRepository::class);
    $objects = $repository->getBaseModel()->newQuery()->get();
    $serializer = new ObjectSerializer();
    return $serializer->serialize($objects);
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
