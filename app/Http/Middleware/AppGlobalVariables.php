<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Log;

class AppGlobalVariables
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $this->auth->user();
        if(isset($user)) {
            $expiresAt = \Carbon\Carbon::now()->addMinutes(5);
            \Cache::put('user-is-online-' . \Auth::user()->id, true, $expiresAt);
            Log::info('user-is-online-' . \Auth::user()->id);
        }
        return $next($request);
    }
}
