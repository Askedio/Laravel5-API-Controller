<?php

namespace Askedio\Laravel5ApiController\Http\Middleware\Auth;

use Closure;
use Askedio\Laravel5ApiController\Exceptions\UnauthorizedHttpException;

class JsonApiAuthBasicMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if((auth()->onceBasic())){
          throw new UnauthorizedHttpException('invalid-credentials');
        }

        return $next($request);
    }

}