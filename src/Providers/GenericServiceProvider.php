<?php

namespace Askedio\Laravel5ApiController\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class GenericServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            \Askedio\Laravel5ApiController\Exceptions\Handler::class
        );

        $this->app->singleton('api', function () {
            return new \Askedio\Laravel5ApiController\Helpers\Api();
        });

        $this->mergeConfigFrom(
            __DIR__.'/../config/jsonapi.php', 'jsonapi'
        );
    }

    /**
     * Register routes, translations, views and publishers.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'jsonapi');

        $this->publishes([
            __DIR__.'/../lang'               => resource_path('lang/vendor/jsonapi'),
            __DIR__.'/../config/jsonapi.php' => config_path('jsonapi.php'),
        ]);

        $router->middleware('jsonapi', \Askedio\Laravel5ApiController\Http\Middleware\JsonApiMiddleware::class);
        $router->middleware('jsonapi.auth.basic', \Askedio\Laravel5ApiController\Http\Middleware\Auth\JsonApiAuthBasicMiddleware::class);

        $this->app->register('Sofa\Eloquence\ServiceProvider');

        response()->macro('jsonapi', function ($code, $value) {
            $apiResponse = new \Askedio\Laravel5ApiController\Http\Responses\ApiResponse();

            return $apiResponse->jsonapi($code, $value);
        });
    }
}
