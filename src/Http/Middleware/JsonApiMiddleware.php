<?php

namespace Askedio\Laravel5ApiController\Http\Middleware;

use Askedio\Laravel5ApiController\Exceptions\BadRequestException;
use Askedio\Laravel5ApiController\Exceptions\NotAcceptableException;
use Askedio\Laravel5ApiController\Exceptions\UnsupportedMediaTypeException;
use Askedio\Laravel5ApiController\Helpers\ApiHelper;
use Closure;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

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



        $pattern = '/application\/vnd\.api\.([\w\d\.]+)\+([\w]+)/';
        if (!preg_match($pattern, $request->header('Accept'), $matches) && $request->header('Accept') != config('jsonapi.accept', 'application/vnd.api+json')) {
            if(config('jsonapi.strict', false)) throw new NotAcceptableException('not-acceptable');
        } else {
          if(isset($matches[1])) ApiHelper::setVersion($matches[1]);
        }

        if ($request->header('Content-Type') != config('jsonapi.accept', 'application/vnd.api+json')) {
            if(config('jsonapi.strict', false))  throw new UnsupportedMediaTypeException('unsupported-media-type');
        }

        return $next($request);
    }
}
