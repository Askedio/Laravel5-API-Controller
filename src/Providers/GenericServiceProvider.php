<?php

namespace Askedio\Laravel5ApiController\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Response;

use Illuminate\Http\JsonResponse;
use Askedio\Laravel5ApiController\Http\Middleware\TransformerMiddleware;

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
  public function boot()
  {
      Response::macro('jsonapi', function ($code = 200, $value) {
        return new JsonResponse($value, $code, [
          'Content-Type' => 'application/vnd.api+json',
          'Accept'       => 'application/vnd.api+json'
        ], true);
      });
  }
}
