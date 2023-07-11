<?php

use A17\Twill\Facades\TwillRoutes;
use App\Http\Controllers\Twill\ExhibitionController;
use Illuminate\Support\Facades\Route;

TwillRoutes::module('exhibitions');
Route::get('exhibitions/augment/{datahub_id}', [ExhibitionController::class, 'augment'])->name('exhibitions.augment');

TwillRoutes::module('galleries');
