<?php

namespace App\Http\Controllers\Twill;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.map-controls')->with([
            'apiKey' => config('twill.google_maps_api_key'),
            'lat' => $request->input('lat'),
            'lng' => $request->input('lng'),
        ]);
    }
}
