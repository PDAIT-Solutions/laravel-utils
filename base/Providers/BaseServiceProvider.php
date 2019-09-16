<?php

namespace PDAit\Base\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class BaseServiceProvider
 *
 * @package PDAit\Base\Providers
 */
class BaseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes(
                [
                        __DIR__.'/../../resources/js/' => resource_path('js/pdait/'),
                        __DIR__.'/../../resources/lang/' => resource_path('lang'),

                ]
                ,
                'pdait'
        );

//        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang/', 'pdait');
    }
}