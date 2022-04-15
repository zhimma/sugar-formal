<?php

namespace App\Http\Middleware;

use Gate;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Services\FaqUserService;

class FaqCheck
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
    public function __construct(Guard $auth,FaqUserService $service)
    {
        $this->auth = $auth;
        $this->service = $service;
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
        if (!is_null($this->auth->user())){
            $this->service->riseByUserEntry($this->auth->user());

            if( $this->service->isForceShowFaqPopup() &&
                !str_contains(url()->current(), 'dashboard/personalPage') &&
                !str_contains(url()->current(), 'users/switch-back') &&
                !Gate::allows('admin', $this->auth->user()) &&
                !Gate::allows('juniorAdmin', $this->auth->user())
                
                ){
                return Redirect::to('/dashboard/personalPage');
            }
        }

        return $next($request);
    }

}
