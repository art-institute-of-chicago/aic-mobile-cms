<?php

use A17\Twill\Facades\TwillRoutes;
use App\Http\Controllers\Twill\ArtworkController;
use App\Http\Controllers\Twill\GalleryController;
use App\Http\Controllers\Twill\SoundController;
use Illuminate\Support\Facades\Route;

TwillRoutes::module('artworks');
Route::get('artworks/augment/{datahub_id}', [ArtworkController::class, 'augment'])->name('artworks.augment');

TwillRoutes::module('galleries');
Route::get('galleries/augment/{datahub_id}', [GalleryController::class, 'augment'])->name('galleries.augment');

TwillRoutes::module('sounds');
Route::get('sounds/augment/{datahub_id}', [SoundController::class, 'augment'])->name('sounds.augment');

TwillRoutes::module('stops');

TwillRoutes::module('tours');
TwillRoutes::module('tours.stops');
