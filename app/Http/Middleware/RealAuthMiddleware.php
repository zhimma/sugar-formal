<?php

namespace App\Http\Middleware;

use Gate;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Services\RealAuthPageService;

class RealAuthMiddleware
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
    public function __construct(Guard $auth,RealAuthPageService $service)
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
            $url_arr = explode('/',url()->current());
            $last_url_seg = array_pop($url_arr);
            $first_url_seg = array_shift($url_arr);
            
            
            if( !$request->ajax() && !$request->real_auth &&
                $first_url_seg!='advance_auth_activate' 
                ){              
                $this->service->forgetRealAuthProcess();

            }

            if(!$request->ajax() && $last_url_seg!='beauty_auth' && !str_contains(url()->current(), 'dashboard/personalPage')) {
                if($this->service->isPassedByAuthTypeId(1) 
                    && !$this->service->isPassedByAuthTypeId(2) 
                    && $this->service->getApplyByAuthTypeId(1)->from_auto
                    && 
                    (
                        !$this->service->getApplyByAuthTypeId(2)
                        || 
                        (
                            $this->service->getApplyByAuthTypeId(2)->status!=2
                            && !$this->service->getApplyByAuthTypeId(2)->latest_unchecked_reply_modify
                        )
                    )
                ) {
                    return Redirect::route('beauty_auth');
                }
            }
        }

        return $next($request);
    }

}
