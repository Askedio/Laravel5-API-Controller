<?php

namespace Askedio\Tests\App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param \Illuminate\Routing\Router $router
     */
    public function boot(Router $router)
    {
        $this->publishes([
          realpath(__DIR__.'/../Database/Migrations') => database_path('migrations'),
        ], 'migrations');
        $this->publishes([
          realpath(__DIR__.'/../Database/Seeds') => database_path('seeds'),
        ], 'seeds');

        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param \Illuminate\Routing\Router $router
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function ($router) {
            require dirname(__FILE__).'/../Http/routes.php';
        });
    }
}
