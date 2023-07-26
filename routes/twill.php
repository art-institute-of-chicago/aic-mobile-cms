<?php

use A17\Twill\Facades\TwillRoutes;
use App\Http\Controllers\Twill\ArtworkController;
use App\Http\Controllers\Twill\ExhibitionController;
use App\Http\Controllers\Twill\GalleryController;
use Illuminate\Support\Facades\Route;

TwillRoutes::module('artworks');
Route::get('artworks/augment/{datahub_id}', [ArtworkController::class, 'augment'])->name('artworks.augment');

TwillRoutes::module('exhibitions');
Route::get('exhibitions/augment/{datahub_id}', [ExhibitionController::class, 'augment'])->name('exhibitions.augment');

TwillRoutes::module('galleries');
Route::get('galleries/augment/{datahub_id}', [GalleryController::class, 'augment'])->name('galleries.augment');
