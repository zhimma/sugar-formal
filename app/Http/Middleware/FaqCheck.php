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
            $url_arr = explode('/',url()->current());
            $last_url_seg = array_pop($url_arr);

            if( !$request->ajax() && $this->service->isForceShowFaqPopup() &&
                $last_url_seg!='dashboard' &&
                $last_url_seg!='dashboard2' &&                
                $last_url_seg!='logout' &&
                !str_contains(url()->current(), 'dashboard/personalPage') &&
                !str_contains(url()->current(), 'users/switch-back') &&
                !str_contains(url()->current(), 'vip') &&
                !str_contains(url()->current(), 'member_auth') &&
                !str_contains(url()->current(), 'pay') &&
                !str_contains(url()->current(), 'dashboard/account_exchange_period') &&
                !str_contains(url()->current(), 'dashboard/forum') &&
                !Gate::allows('admin', $this->auth->user()) &&
                !Gate::allows('juniorAdmin', $this->auth->user())
                
                ){
                $sess_go_faq_num = session()->get('redirect_to_faq_num',0);
                if($sess_go_faq_num<=3) {
                    session()->put('redirect_to_faq_num',$sess_go_faq_num+1);
                    return Redirect::to('/dashboard/personalPage');
                }
            }
            
        }
        session()->forget('redirect_to_faq_num');
        return $next($request);
    }

}
