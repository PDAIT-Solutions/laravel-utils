<?php

namespace PDAit\Base\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;


class LocaleServiceProvider extends ServiceProvider
{

    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    public function boot()
    {
        Route::pattern('_locale', \implode('|', $this->app['config']['app.locales']));

        Route::matched(
            function (\Illuminate\Routing\Events\RouteMatched $event) {
                // Get language from route.
                $locale = $event->route->parameter('_locale');

                // Ensure, that all built urls would have "_locale" parameter set from url.
                url()->defaults(array('_locale' => $locale));

                // Change application locale.
                app()->setLocale($locale);

                // Unset _locale parameter
                unset($event->route->parameters['_locale']);
            }
        );


        if (file_exists(base_path('routes/home.php'))) {
            Route::middleware('web')
                ->group(base_path('routes/home.php'));
        }
    }
}
