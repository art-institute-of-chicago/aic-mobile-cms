<?php

namespace App\Http\Controllers\Twill;

use App\Http\Controllers\Controller;
use App\Models\Floor;
use Illuminate\Http\Request;

// The boundary of the museum campus
const MAP_BOUNDS = [
    'north' => 41.88085384238198,
    'south' => 41.8783542495326,
    'east' => -87.6208768609257,
    'west' => -87.62429309521068,
];

// The center of the museum
const DEFAULT_LOCATION = [
    'latitude' => 41.8795425,
    'longitude' => -87.6235470,
];

const DEFAULT_FLOOR = '1';

class MapController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.map-controls')->with([
            'apiKey' => config('twill.google_maps_api_key'),
            'latitude' => $request->input('latitude') ?? DEFAULT_LOCATION['latitude'],
            'longitude' => $request->input('longitude') ?? DEFAULT_LOCATION['longitude'],
            'floor' => $request->input('floor') ?? DEFAULT_FLOOR,
            'bounds' => json_encode(MAP_BOUNDS),
            'floors' => Floor::all()->toJson(),
        ]);
    }
}
