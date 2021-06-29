<?php

namespace App\Exceptions;

use Exception;
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
    protected $dontFlash = [
        'password',
        'password_confirmation',
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
        info($exception->getMessage(), [
            'url' => request()->url(),
            'input' => request()->all()
        ]);
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
//        if(!$exception instanceof ValidationException && !$exception instanceof \Illuminate\Auth\AuthenticationException) {
//            return response()->view('errors.exception',
//                [ 'exception' => $exception->getMessage() == null ? null : $exception->getMessage()]);
//        }
        return parent::render($request, $exception);
        //return redirect('/error');
        //return view('errors.exception')->with('exception', $exception->getMessage() == null ? null : $exception->getMessage());
    }
}
