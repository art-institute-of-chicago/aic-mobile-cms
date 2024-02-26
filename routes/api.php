<?php

use App\Http\Controllers\Api\AudioFiles;
use App\Http\Controllers\Api\Galleries;
use App\Http\Controllers\Api\Data;
use App\Http\Controllers\Api\Dashboard;
use App\Http\Controllers\Api\Exhibitions;
use App\Http\Controllers\Api\GeneralInfo;
use App\Http\Controllers\Api\Messages;
use App\Http\Controllers\Api\MapAnnotations;
use App\Http\Controllers\Api\MapFloors;
use App\Http\Controllers\Api\Objects;
use App\Http\Controllers\Api\Search;
use App\Http\Controllers\Api\TourCategories;
use App\Http\Controllers\Api\Tours;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;

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
Route::get('/appData-v3', function () {
    // Listed in the order the sections appear in the Drupal appData.json
    $controllers = collect([
        Dashboard::class,
        GeneralInfo::class,
        Galleries::class,
        Objects::class,
        AudioFiles::class,
        Tours::class,
        MapAnnotations::class,
        MapFloors::class,
        Messages::class,
        TourCategories::class,
        Exhibitions::class,
        Data::class,
        Search::class,
    ]);
    // 3600 = 1 hour
    return Cache::remember('apiData', 3600, function () use ($controllers) {
        return $controllers->reduce(function ($output, $controller) {
            // Instantiate and invoke each controller, then merge into the output
            return $output->merge((new $controller())());
        }, collect());
    });
});
Route::get('/audio_files', AudioFiles::class)->name('audio_files');
Route::get('/dashboard', Dashboard::class)->name('dashboard');
Route::get('/data', Data::class)->name('data');
Route::get('/exhibitions', Exhibitions::class)->name('exhibitions');
Route::get('/galleries', Galleries::class)->name('galleries');
Route::get('/general_info', GeneralInfo::class)->name('general_info');
Route::get('/map_annotations', MapAnnotations::class)->name('map_annotations');
Route::get('/map_floors', MapFloors::class)->name('map_floors');
Route::get('/messages', Messages::class)->name('messages');
Route::get('/objects', Objects::class)->name('objects');
Route::get('/search', Search::class)->name('search');
Route::get('/tour_categories', TourCategories::class)->name('tour_categories');
Route::get('/tours', Tours::class)->name('tours');
