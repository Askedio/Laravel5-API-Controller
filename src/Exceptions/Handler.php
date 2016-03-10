<?php

namespace Askedio\Laravel5ApiController\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        JsonException::class,
    ];

    /**
     * Report or log an exception.
     *
     * @param Exception $e
     *
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request   $request
     * @param Exception $e
     *
     * @return Response
     */
    public function render($request, Exception $e)
    {
        //config('app.debug') ||
        if (!$request->is(config('jsonapi.url', 'api/*'))) {
            return parent::render($request, $e);
        }

        return $this->handle($request, $e);
    }

    /**
     * Convert the Exception into a JSON HTTP Response.
     *
     * @param Request   $request
     * @param Exception $e
     *
     * @return JSONResponse
     */
    private function handle($request, Exception $e)
    {
        if ($e instanceof JsonException) {
            $data = $e->toArray();
            $status = $e->getStatus();
        }

        if ($e instanceof NotFoundHttpException) {
            $data = array_merge([
                'id'     => 'not_found',
                'status' => '404',
            ], config('errors.not_found'));

            $status = 404;
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            $data = array_merge([
                'id'     => 'method_not_allowed',
                'status' => '405',
            ], config('errors.method_not_allowed'));

            $status = 405;
        }

        unset($data['id']);

        return new JsonResponse(['errors' => $data], $status, [
          'Content-Type' => config('jsonapi.content-type', 'application/vnd.api+json'),
        ], true);
    }
}
