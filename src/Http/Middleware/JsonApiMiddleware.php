<?php

namespace Askedio\Laravel5ApiController\Http\Middleware;

use Askedio\Laravel5ApiController\Exceptions\BadRequestException;
use Askedio\Laravel5ApiController\Exceptions\NotAcceptableException;
use Askedio\Laravel5ApiController\Exceptions\UnsupportedMediaTypeException;
use Askedio\Laravel5ApiController\Helpers\ApiHelper;
use Closure;

class JsonApiMiddleware
{
    private $request;

    private $allowedGetVariables = [
                  'include',
                  'fields',
                  'page',
                  'limit',
                  'sort',
                  'search',
                 ];

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
        $this->request = $request;
        $this->checkGetVars();
        $this->checkAccept();
        $this->checkContentType();

        return $next($request);
    }

    private function checkGetVars()
    {
        if (!$this->request->isMethod('get')) {
            return false;
        }

        $_check = array_except($this->request->all(), config('jsonapi.allowed_get', $this->allowedGetVariables));
        if (!empty($_check)) {
            /* TO-DO: exception should render array of errors */
            throw new BadRequestException('invalid_get', rtrim(implode(', ', array_keys($_check)), ', '));
        }
    }

    private function checkAccept()
    {
        if (!preg_match('/application\/vnd\.api\.([\w\d\.]+)\+([\w]+)/', $this->request->header('Accept'), $matches)
              && $this->request->header('Accept') != config('jsonapi.accept', 'application/vnd.api+json')) {
            if (config('jsonapi.strict', false)) {
                throw new NotAcceptableException('not-acceptable');
            }
        } else {
            if (isset($matches[1])) {
                ApiHelper::setVersion($matches[1]);
            }
        }
    }

    private function checkContentType()
    {
        if ($this->request->header('Content-Type') != config('jsonapi.content_type', 'application/vnd.api+json')) {
            if (config('jsonapi.strict', false)) {
                throw new UnsupportedMediaTypeException('unsupported-media-type');
            }
        }
    }
}
