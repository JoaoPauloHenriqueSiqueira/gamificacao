<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\UrlGenerator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Repositories\Contracts\CompanyRepositoryInterface',
            'App\Repositories\CompanyRepository'
        );

        $this->app->bind(
            'App\Repositories\Contracts\UserRepositoryInterface',
            'App\Repositories\UserRepository'
        );

        $this->app->bind(
            'App\Repositories\Contracts\UserTokenRepositoryInterface',
            'App\Repositories\UserTokenRepository'
        );

        $this->app->bind(
            'App\Repositories\Contracts\CampaignRepositoryInterface',
            'App\Repositories\CampaignRepository'
        );

        $this->app->bind(
            'App\Repositories\Contracts\PhotoRepositoryInterface',
            'App\Repositories\PhotoRepository'
        );

        $this->app->bind(
            'App\Repositories\Contracts\VideoRepositoryInterface',
            'App\Repositories\VideoRepository'
        );

        $this->app->bind(
            'App\Repositories\Contracts\AlbumRepositoryInterface',
            'App\Repositories\AlbumRepository'
        );

        $this->app->bind(
            'App\Repositories\Contracts\AlbumVideosRepositoryInterface',
            'App\Repositories\AlbumVideosRepository'
        );

        $this->app->bind(
            'App\Repositories\Contracts\CreditsCardsRepositoryInterface',
            'App\Repositories\CreditsCardsRepository'
        );

        $this->app->bind(
            'App\Repositories\Contracts\ScrapRepositoryInterface',
            'App\Repositories\ScrapRepository'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        // if(env('APP_ENV') !== 'local') {
        //     $url->forceScheme('https');
        // }
    }
}
