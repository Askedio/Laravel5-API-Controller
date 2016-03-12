<?php

namespace Askedio\Laravel5ApiController\Http\Middleware;

use Askedio\Laravel5ApiController\Exceptions\ApiException;
use Askedio\Laravel5ApiController\Exceptions\BadRequestException;
use Askedio\Laravel5ApiController\Exceptions\NotAcceptableException;
use Askedio\Laravel5ApiController\Exceptions\UnsupportedMediaTypeException;
use Askedio\Laravel5ApiController\Facades\Api;
use Closure;

class JsonApiMiddleware
{
    private $request;

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

    /**
     * Check GET input variaibles.
     *
     * @return void
     */
    private function checkGetVars()
    {
        if (!$this->request->isMethod('get')) {
            return false;
        }

        $_check = array_except($this->request->all(), config('jsonapi.allowed_get'));
        if (!empty($_check)) {
            $_errors = [];

            foreach ($_check as $_field => $_val) {
                array_push($_errors, [
                'code'   => 0,
                'source' => ['pointer' => $_field],
                'title'  => config('errors.invalid_get.title'),
              ]);
            }

            ApiException::setDetails(['errors' => $_errors]);
            throw new BadRequestException('invalid_get');
        }
    }

    /**
     * Check Accept Header.
     *
     * @return void
     */
    private function checkAccept()
    {
        if (!preg_match('/application\/vnd\.api\.([\w\d\.]+)\+([\w]+)/', $this->request->header('Accept'), $matches)
              && $this->request->header('Accept') != config('jsonapi.accept')) {
            if (config('jsonapi.strict')) {
                ApiException::setDetails(config('jsonapi.accept'));
                throw new NotAcceptableException('not-acceptable');
            }
        } else {
            if (isset($matches[1])) {
                Api::setVersion($matches[1]);
            }
        }
    }

    /**
     * Check Content-Type Header.
     *
     * @return void
     */
    private function checkContentType()
    {
        if ($this->request->header('Content-Type') != config('jsonapi.content_type')) {
            if (config('jsonapi.strict')) {
                ApiException::setDetails(config('jsonapi.content_type'));
                throw new UnsupportedMediaTypeException('unsupported-media-type');
            }
        }
    }
}
