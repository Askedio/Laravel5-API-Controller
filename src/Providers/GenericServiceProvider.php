<?php

namespace Askedio\Laravel5ApiController\Providers;

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

      $loader = \Illuminate\Foundation\AliasLoader::getInstance();
      $loader->alias('Api', \Askedio\Laravel5ApiController\Facades\Api::class);
      $loader->alias('ApiException', \Askedio\Laravel5ApiController\Exceptions\ApiException::class);
      $loader->alias('ApiResponse', \Askedio\Laravel5ApiController\Http\Responses\ApiResponse::class);
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
        return response()->json($value, $code, [
          'Content-Type' => config('jsonapi.content_type', 'application/vnd.api+json'),
        ], true);
      });

      $this->publishes([
        __DIR__.'/config/jsonapi.php' => config_path('jsonapi.php'),
        __DIR__.'/config/errors.php'  => config_path('errors.php'),
    ]);
  }
}
