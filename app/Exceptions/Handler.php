<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Throwable;

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
    public function report(Throwable $exception)
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
    public function render($request, Throwable $exception)
    {
//        if(!$exception instanceof ValidationException && !$exception instanceof \Illuminate\Auth\AuthenticationException) {
//            return response()->view('errors.exception',
//                [ 'exception' => $exception->getMessage() == null ? null : $exception->getMessage()]);
//        }
        if($exception instanceof \Illuminate\Session\TokenMismatchException || $exception == 'CSRF token mismatch.'){
            logger('TokenMismatchException occurred, url: ' . url()->current());
            logger('is $exception instanceof TokenMismatchException' . $exception instanceof \Illuminate\Session\TokenMismatchException);
            return redirect()
                    ->back()
                    ->withInput($request->except('password', '_token'))
                    ->withError('驗證已過期，請再試一次');
        }
        return parent::render($request, $exception);
        //return redirect('/error');
        //return view('errors.exception')->with('exception', $exception->getMessage() == null ? null : $exception->getMessage());
    }

    /**
     * Get the default context variables for logging.
     *
     * @return array
     */

    protected function context()
    {
        try {
            return array_filter([
                'url' => Request::fullUrl(),
                'input' => Request::except(['password', 'password_confirmation']),
                'userId' => Auth::id(),
                'email' => Auth::user() ? Auth::user()->email : null,
            ]);
        } catch (Throwable $e) {
            return [];
        }
    }
}
