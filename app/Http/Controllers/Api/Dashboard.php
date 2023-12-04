<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Serializers\DashboardSerializer;
use App\Repositories\TourRepository;
use Illuminate\Support\Facades\App;

class Dashboard extends Controller
{
    public function __invoke()
    {
        $tourRepository = App::make(TourRepository::class);
        $featuredTours = $tourRepository->getBaseModel()->newQuery()->visible()->published()->featured()->get();
        $dashboardSerializer = new DashboardSerializer();
        return $dashboardSerializer->serialize($featuredTours);
    }
}
