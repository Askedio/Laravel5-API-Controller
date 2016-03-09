<?php

namespace Askedio\Laravel5ApiController\Providers;

use Illuminate\Http\JsonResponse;
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
  }

  /**
   * Register routes, translations, views and publishers.
   *
   * @return void
   */
  public function boot()
  {
      Response::macro('jsonapi', function ($code, $value) {
        return new JsonResponse($value, $code, [
          'Content-Type' => 'application/vnd.api+json',
        ], true);
      });
  }
}
