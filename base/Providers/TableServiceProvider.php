<?php

namespace PDAit\Base\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

/**
 * Class TableServiceProvider
 *
 * @package PDAit\Base\Providers
 */
class TableServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /*
         * Directive for fast rendering table
         */
        Blade::directive(
                'table',
                function ($table) {
                    return "<?php
             if(!($table instanceof \PDAit\Base\Table\Model\Table)){
                throw new \InvalidArgumentException('If you use @table directive you must pass \PDAit\Base\Table\Model\Table');
             }
             echo view('pdait::table/_table', ['table' => $table]); 
             ?>";
                }
        );

        /*
         * Loading custom views folder
         */
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'pdait');
    }
}