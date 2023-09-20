<?php

namespace App\Providers;

use A17\Twill\Facades\TwillNavigation;
use A17\Twill\View\Components\Navigation\NavigationLink;
use App\Libraries\Api\Consumers\GuzzleApiConsumer;
use App\Libraries\DamsImageService;
use App\Models;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
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
        if (App::environment(['test', 'production'])) {
            URL::forceScheme('https');
        }
        TwillNavigation::addLink(
            NavigationLink::make()->forModule('audios')
        );
        TwillNavigation::addLink(
            NavigationLink::make()->forModule('galleries')
        );
        TwillNavigation::addLink(
            NavigationLink::make()
                ->title('Objects')
                ->forModule('collectionObjects')
                ->doNotAddSelfAsFirstChild()
                ->setChildren([
                    NavigationLink::make()->title('Collection Objects')->forModule('collectionObjects'),
                    NavigationLink::make()->title('Loan Objects')->forModule('loanObjects'),
                ])
        );
        TwillNavigation::addLink(
            NavigationLink::make()->forModule('selectors')
        );
        TwillNavigation::addLink(
            NavigationLink::make()->forModule('stops')
        );
        TwillNavigation::addLink(
            NavigationLink::make()->forModule('tours')
        );
        Relation::morphMap([
            'audio' => Models\Audio::class,
            'collectionObject' => Models\CollectionObject::class,
            'gallery' => Models\Gallery::class,
            'loanobject' => Models\LoanObject::class,
            'selector' => Models\Selector::class,
            'stop' => Models\Stop::class,
            'tour' => Models\Tour::class,
        ]);
    }
}
