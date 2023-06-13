<?php

namespace App\Providers;

use App\Libraries\DamsImageService;
use App\Libraries\Api\Consumers\GuzzleApiConsumer;
use A17\Twill\Facades\TwillNavigation;
use A17\Twill\View\Components\Navigation\NavigationLink;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('damsimageservice', function ($app) {
            return new DamsImageService();
        });
        $this->app->singleton('ApiClient', function ($app) {
            return new GuzzleApiConsumer([
                'base_uri' => config('api.base_uri'),
                'exceptions' => false,
                'decode_content' => true, // Explicit default
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        TwillNavigation::addLink(
            NavigationLink::make()->forModule('galleries')
        );
    }
}
