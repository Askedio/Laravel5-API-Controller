<?php

namespace Askedio\Laravel5ApiController\Exceptions;

use Askedio\Laravel5ApiController\Http\Responses\ApiResponse;
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
        JsonException::class,
    ];

    /**
     * Report or log an exception.
     *
     * @param Exception $exception
     *
     * @return void
     */
    public function report(Exception $exception)
    {
        return parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request   $request
     * @param Exception $exception
     *
     * @return Response
     */
    public function render($request, Exception $exception)
    {
        if (!$request->is(config('jsonapi.url'))) {
            return parent::render($request, $exception);
        }

        return $this->handle($request, $exception);
    }

    /**
     * Convert the Exception into a JSON HTTP Response.
     *
     * @param Request   $request
     * @param Exception $exception
     *
     * @return ApiResponse
     */
    private function handle($request, Exception $exception)
    {

        /* translate HttpExceptions to json api style */
        if ($exception instanceof HttpException) {
            $code = $exception->getStatusCode();
            $data = [
             'status' => $exception->getStatusCode(),
             'detail' => $exception->getMessage(),
            ];
            if (env('APP_DEBUG', false)) {
                $data['source'] = ['line '.$exception->getLine() => $exception->getFile()];
            }

            return response()->jsonapi($code, ['errors' => $data]);
        }

        /* custom exception class */
        if ($exception instanceof JsonException) {
            $data = $exception->getError();
            $code = $exception->getStatusCode();

            return response()->jsonapi($code, ['errors' => $data]);
        }

        /* not an exception we manage so generic error or if debug, the real exception */
        if (!env('APP_DEBUG', false)) {
            // TO-DO: needs to check if function exists.
            $code = 500; //$exception->getStatusCode();
            $data = [
             'status' => 500,
             'detail' => 'Unknown Exception',
            ];

            return response()->jsonapi($code, ['errors' => $data]);
        }

        return parent::render($request, $exception);
    }
}
