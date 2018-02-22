<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;
use \Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Exception $exception Exception
     *
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request   Request HTTP
     * @param \Exception               $exception Exception
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($request->route()->getPrefix() === 'api') {
            if ($exception instanceof ModelNotFoundException) {
                $code = Response::HTTP_NOT_FOUND;
                $msg = __('api.page_not_found');
            }

            return response()->json([
                'meta' => [
                    'status' => __('api.failed'),
                    'code' => $code,
                ],
                'error' => [
                    'message' => $msg,
                ],
            ], $code);
        }
        return parent::render($request, $exception);
    }
}
