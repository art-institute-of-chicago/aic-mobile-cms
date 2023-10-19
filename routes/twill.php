<?php

use A17\Twill\Facades\TwillRoutes;
use App\Http\Controllers\Twill\AudioController;
use App\Http\Controllers\Twill\CollectionObjectController;
use App\Http\Controllers\Twill\GalleryController;
use App\Http\Controllers\Twill\MapController;
use App\Http\Controllers\Twill\StopController;
use App\Http\Controllers\Twill\TourController;
use Illuminate\Support\Facades\Route;

Route::get(
    'collectionObjects/augment/{datahub_id}',
    [CollectionObjectController::class, 'augment']
)->name('collectionObjects.augment');
TwillRoutes::module('collectionObjects');

Route::get('galleries/augment/{datahub_id}', [GalleryController::class, 'augment'])->name('galleries.augment');
TwillRoutes::module('galleries');

TwillRoutes::module('loanObjects');

TwillRoutes::module('selectors');

Route::get('audio/augment/{datahub_id}', [AudioController::class, 'augment'])->name('audio.augment');
TwillRoutes::module('audio');

Route::get('stops/createWithObject', [StopController::class, 'createWithObject'])->name('stops.createWithObject');
Route::get('stops/createWithAudio', [StopController::class, 'createWithAudio'])->name('stops.createWithAudio');
TwillRoutes::module('stops');

Route::get('tours/createWithAudio', [TourController::class, 'createWithAudio'])->name('tours.createWithAudio');
TwillRoutes::module('tours');

Route::get('map', [MapController::class, 'index'])->name('map.index');

TwillRoutes::module('labels');

TwillRoutes::module('floors');

TwillRoutes::module('annotations');
TwillRoutes::module('annotationTypes');
TwillRoutes::module('annotationCategories');
