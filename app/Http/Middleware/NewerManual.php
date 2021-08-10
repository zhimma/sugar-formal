<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

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

            if( $this->auth->user()->engroup==1 &&
                !$this->auth->user()->isVip() &&
                !$this->auth->user()->isPhoneAuth() &&
                !str_contains(url()->current(), 'vip') &&
                !str_contains(url()->current(), 'member_auth') &&
                !str_contains(url()->current(), 'pay') ){
                return Redirect::to('member_auth')->with('message', 'male_alert');
            }
        }

        return $next($request);
    }

}
