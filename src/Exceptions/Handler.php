<?php

namespace Askedio\Laravel5ApiController\Exceptions;

use Askedio\Laravel5ApiController\Helpers\JsonResponse;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
        if (!$request->is(config('jsonapi.url'))) {
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
            $data = $e->getError();
            $code = $e->getStatusCode();
        } elseif ($e instanceof HttpException) {
            $code = $e->getStatusCode();
            $data = [
             'status' => $e->getStatusCode(),
             'detail' => $e->getMessage(),
            ];
            if (env('APP_DEBUG', false)) {
                $data['source'] = ['line '.$e->getLine() => $e->getFile()];
            }
            $data = ['errors' => $data];
        } else {
            if (!env('APP_DEBUG', false)) {
                $code = $e->getStatusCode();
                $data = [
               'status' => 500,
               'detail' => 'Unknown Exception',
              ];
                $data = ['errors' => $data];
            } else {
                return parent::render($request, $e);
            }
        }

        return JsonResponse::error($code, $data);
    }
}
