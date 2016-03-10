<?php

namespace Askedio\Laravel5ApiController\Http\Middleware;

use Askedio\Laravel5ApiController\Exceptions\BadRequestException;
use Askedio\Laravel5ApiController\Helpers\ApiHelper;
use Closure;

class JsonApiMiddleware
{
    /**
     * Filter requests based on Accept and Content-Type matches.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $_allowed = config('jsonapi.allowed_get', [
  'include',
  'fields',
  'page',
  'limit',
  'sort',
 ]);

        if ($request->isMethod('get')) {
            foreach ($request->all() as $var => $val) {
                if (!in_array($var, $_allowed)) {
                    throw new BadRequestException('invalid_get', $var);
                }
            }
        }

      // to-do: switch to exceptions
        if ($request->header('Accept') != config('jsonapi.content-type', 'application/vnd.api+json')) {
            return ApiHelper::error(406, 'Not Acceptable');
        }

        if ($request->header('Content-Type') != config('jsonapi.accept', 'application/vnd.api+json')) {
            return ApiHelper::error(415, 'Unsupported Media Type');
        }

        return $next($request);
    }
}
