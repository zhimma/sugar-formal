<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Throwable;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Log;
use App\Models\MessageErrorLog;

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
    public function report(Throwable $exception) {
        if (app()->bound('sentry') && $this->shouldReport($exception)) {
            app('sentry')->captureException($exception);
        }
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
        if($exception instanceof \Illuminate\Session\TokenMismatchException){
            logger("TokenMismatchException occurred, url: " . url()->current());
            logger("Referer: " . request()->headers->get("referer"));
            logger("UserAgent: " . request()->headers->get("User-Agent"));
            // logger("IP: " . request()->ip());
            return redirect()
                    ->back()
                    ->withInput($request->except('password', '_token'))
                    ->withError('驗證已過期，請再試一次');
        }
        // if ($exception->getStatusCode() == 403) {
        //     return response()->view('errors.exception',[ 'exception' => '網站維護中:']);
        // }
        
        // if($exception instanceof \Illuminate\Http\Exceptions\ThrottleRequestsException){
        //     return parent::render($request, $exception);
        // }
        // if(!$exception instanceof ValidationException && !$exception instanceof AuthenticationException) {
        //     return response()->view('errors.exception', [ 'exception' => $exception->getMessage() == null ? null : $exception->getMessage()]);
        // }

        if ($exception instanceof AuthenticationException && Request::is('api/*')) {
            return response()->json(['status' => 1, 'message' => 'Token is Invalid'], 401);
        }

        if(str_contains(url()->current(),'postMsg')) {
            MessageErrorLog::create(['from_id'=>$request->user()?$request->user()->id:0
                                    ,'to_id'=>  $request->to
                                    ,'content'=>$request->msg
                                    ,'pic'=>json_encode($request->file('images')??[])                                                            
                                    ,'error'=>var_export($exception,true)
                                    ,'error_return_data'=>json_encode($exception)
                                ]);
        }

        return parent::render($request, $exception);
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