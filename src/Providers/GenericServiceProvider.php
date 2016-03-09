<?php

namespace Askedio\Laravel5ApiController\Providers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
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
          'Content-Type' => config('jsonapi.content-type', 'application/vnd.api+json'),
        ], true);
      });
  }
}
