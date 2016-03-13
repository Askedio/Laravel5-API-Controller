<?php

namespace Askedio\Laravel5ApiController\Exceptions;

use Askedio\Laravel5ApiController\Http\Responses\ApiResponse;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;

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

        /* custom exception class */
        if ($exception instanceof ApiException) {
            $data = $exception->getError();
            $code = $exception->getStatusCode();

            return response()->jsonapi($code, ['errors' => $data]);
        }

        /* not an exception we manage so generic error or if debug, the real exception */
        if (!env('APP_DEBUG', false)) {
            $code   = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500;
            $detail = method_exists($exception, 'getMessage') ? $exception->getMessage() : 'Unknown Exception.';
            $data   = array_filter([
             'status' => $code,
             'detail' => $detail,
           ]);

            return response()->jsonapi($code, ['errors' => $data]);
        }

        return parent::render($request, $exception);
    }
}
