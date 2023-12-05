<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class Data extends Controller
{
    public function __invoke()
    {
        return ['data' => config('uris')];
    }
}
