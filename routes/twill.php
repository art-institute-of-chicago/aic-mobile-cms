<?php

use A17\Twill\Facades\TwillRoutes;
use Illuminate\Support\Facades\Route;

TwillRoutes::module('galleries');
Route::get('galleries/augment/{datahub_id}', [GalleryController::class, 'augment'])->name('galleries.augment');
