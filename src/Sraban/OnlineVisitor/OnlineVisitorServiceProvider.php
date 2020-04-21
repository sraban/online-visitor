<?php

namespace Sraban\OnlineVisitor;

use Illuminate\Support\ServiceProvider;

class OnlineVisitorServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        if (method_exists($this, 'loadRoutesFrom')) {
            $this->loadRoutesFrom(__DIR__.'/../../routes.php');
        }

        if (method_exists($this, 'loadMigrationsFrom')) {
            $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        }

        if (method_exists($this, 'package')) {
            $this->package('sraban/online-visitor', 'online-visitor', __DIR__ . '/../../');
        }

        if (method_exists($this, 'loadViewsFrom')) {
            $this->loadViewsFrom(__DIR__.'/../../views', 'online-visitor');
        }

        if (method_exists($this, 'publishes')) {
            $this->publishes([
                __DIR__.'/../../views' => base_path('resources/views/vendor/online-visitor'),
            ]);
        }
        
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Sraban\OnlineVisitor\EmployeeController');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

}
