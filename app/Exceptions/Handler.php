<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
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
    // protected $dontFlash = [
    //     'password',
    //     'password_confirmation',
    // ];

    protected $dontFlash = [
        // 
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {   
        if($exception instanceof ThrottleRequestsException){
            return parent::render($request, $exception);
        }
        if(!$exception instanceof ValidationException && !$exception instanceof AuthenticationException) {
            return response()->view('errors.exception', [ 'exception' => $exception->getMessage() == null ? null : $exception->getMessage()]);
        }
    //    $requestStr =  $request->all();
    //    \Illuminate\Support\Facades\Log::info('Exception: ' . $exception->getMessage() . ', URI: ' . $_SERVER["REQUEST_URI"] . ', request: ' . print_r($requestStr, true));

        return parent::render($request, $exception);
    }
}
