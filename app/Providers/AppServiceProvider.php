<?php

namespace App\Providers;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        // if (config('app.env') === 'production') {
        //     URL::forceScheme('https');
        // }    
        // if(config('app.env') === 'production') {
        //     \URL::forceScheme('https');
        // }    
        // if ($this->app->environment('production')) {
        //     $this->app['request']->server->set('HTTPS','on');
        //     URL::forceSchema('https');
        // }
    }
}