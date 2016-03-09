<?php

namespace Askedio\Laravel5ApiController\Http\Middleware;

use Closure;
use Askedio\Laravel5ApiController\Helpers\ApiHelper;

class JsonApiMiddleware
{
    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      
        if($request->header('Accept')       != 'application/vnd.api+json') return ApiHelper::throwException(415, 'Unsupported Media Type');
        if($request->header('Content-Type') != 'application/vnd.api+json') return ApiHelper::throwException(406, 'Not Acceptable');

        return $next($request);
    }

}