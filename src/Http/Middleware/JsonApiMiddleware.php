<?php

namespace Askedio\Laravel5ApiController\Http\Middleware;

use Askedio\Laravel5ApiController\Exceptions\BadRequestException;
use Askedio\Laravel5ApiController\Exceptions\UnsupportedMediaTypeException;
use Askedio\Laravel5ApiController\Exceptions\NotAcceptableException;
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

        if ($request->isMethod('get')) {
            foreach ($request->all() as $var => $val) {
                if (!in_array($var, config('jsonapi.allowed_get', [
                  'include',
                  'fields',
                  'page',
                  'limit',
                  'sort',
                  'search',
                 ]))) {
                    throw new BadRequestException('invalid_get', $var);
                }
            }
        }

        if ($request->header('Accept') != config('jsonapi.content-type', 'application/vnd.api+json')) {
            throw new NotAcceptableException('not-acceptable');
        }

        if ($request->header('Content-Type') != config('jsonapi.accept', 'application/vnd.api+json')) {
            throw new UnsupportedMediaTypeException('unsupported-media-type');
        }

        return $next($request);
    }
}
