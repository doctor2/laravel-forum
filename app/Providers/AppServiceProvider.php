<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

         \View::composer('*', function($view){
             $channels = \Cache::rememberForever('channels', function () {
                 return \App\Channel::all();
             });

            $view->with('channels', $channels);
        });

        // \View::composer('threads.create', function($view){
        //     $view->with('channels', \App\Channel::all());
        // });
        // \View::share('channels', \App\Channel::all());
        \Validator::extend('spamfree', 'App\Rules\SpamFree@passes');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if($this->app->isLocal())
        {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
    }
}
