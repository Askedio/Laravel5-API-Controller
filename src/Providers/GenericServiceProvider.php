<?php

namespace Askedio\Laravel5ApiController\Providers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Response;

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

    $this->mergeConfigFrom(
        __DIR__.'/../config/errors.php', 'errors'
    );

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
      $router->middleware('jsonapi', \Askedio\Laravel5ApiController\Http\Middleware\JsonApiMiddleware::class);

      Response::macro('jsonapi', function ($code, $value) {
        return new JsonResponse($value, $code, [
          'Content-Type' => config('jsonapi.content_type', 'application/vnd.api+json'),
        ], true);
      });

    $this->publishes([
        __DIR__.'/config/jsonapi.php' => config_path('jsonapi.php'),
        __DIR__.'/config/errors.php' => config_path('errors.php'),
    ]);
  }
}
