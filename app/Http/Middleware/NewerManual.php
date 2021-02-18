<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Session;

class NewerManual
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
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
        $user_meta = view()->shared('user_meta');
        if (!is_null($this->auth->user())){
            if (!$user_meta->isAllSet($this->auth->user()->engroup)  && $this->auth->user()->isReadManual == 0) {
                return response()->view('new.dashboard.newer_manual',['user'=> auth()->user()]);
            }
        }
        return $next($request);
    }

}
