<?php

use A17\Twill\Facades\TwillRoutes;
use App\Http\Controllers\Twill\ArtworkController;
use App\Http\Controllers\Twill\GalleryController;
use App\Http\Controllers\Twill\SoundController;
use App\Http\Controllers\Twill\StopController;
use App\Http\Controllers\Twill\TourController;
use Illuminate\Support\Facades\Route;

Route::get('artworks/augment/{datahub_id}', [ArtworkController::class, 'augment'])->name('artworks.augment');
TwillRoutes::module('artworks');

Route::get('galleries/augment/{datahub_id}', [GalleryController::class, 'augment'])->name('galleries.augment');
TwillRoutes::module('galleries');

TwillRoutes::module('selectors');

Route::get('sounds/augment/{datahub_id}', [SoundController::class, 'augment'])->name('sounds.augment');
TwillRoutes::module('sounds');

Route::get('stops/create-with-artwork', [StopController::class, 'createWithArtwork'])->name('stops.create-with-artwork');
Route::get('stops/create-with-sound', [StopController::class, 'createWithSound'])->name('stops.create-with-sound');
TwillRoutes::module('stops');

Route::get('tours/create-with-sound', [TourController::class, 'createWithSound'])->name('tours.create-with-sound');
TwillRoutes::module('tours');

TwillRoutes::module('selectors');
