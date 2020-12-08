<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Session;

class CheckAccountStatus
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
        if(!in_array($_SERVER['REQUEST_URI'], ['/dashboard/new_vip','/dashboard/vip','/dashboard/upgradepay_ec'])){
            if (!is_null($this->auth->user())){
                //0:帳號關閉中 1:帳號開啟中(預設)
                if ($this->auth->user()->accountStatus == 0) {
                    if(Session::get('needLogOut') == 'Y'){
                        //logger('middleware=>'.Session::get('needLogOut'));
                        Session::put('needLogOut','N');
                    }
                    return response()->view('new.dashboard.openCloseAccount',['user'=> auth()->user()]);
                }
            }
        }
        return $next($request);
    }

}