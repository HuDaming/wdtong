<?php

namespace Hudm\Wdtong;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Wdtong::class, function (){
            return new Wdtong(config('services.wdtong'));
        });

        $this->app->alias(Wdtong::class, 'wdtong');
    }

    public function provides()
    {
        return [Wdtong::class, 'wdtong'];
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
