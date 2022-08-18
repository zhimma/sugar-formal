<?php

namespace App\Http\Controllers;

use View;
use App\Services\RealAuthPageService;

class BaseController extends Controller
{
    //
    protected $user, $user_meta, $userIsVip, $unread, $valueAddedServices,$rap_service;

    public function __construct() {
        if(\Request::route() && \Request::route()->getName() != "showMessages"){
            $this->middleware('global');
            $this->middleware(function ($request, $next) {
                $this->user = view()->shared('user');
                $this->user_meta = view()->shared('user_meta');
                $this->userIsVip = view()->shared('isVip');
                $this->userIsFreeVip = view()->shared('isFreeVip');
                $this->userVipData = view()->shared('vipData');
                $this->unread = view()->shared('unread');
                $this->valueAddedServices = view()->shared('valueAddedServices');
                if(!view()->shared('self_auth_video_allusers') && $this->rap_service && $this->rap_service->riseByUserEntry(auth()->user())->isAllowUseVideoChat()) {
                    $self_auth_video_allusers = \DB::table('role_user')->leftJoin('users', 'role_user.user_id', '=', 'users.id')->where('users.id', '<>', auth()->id())->get();
                    view()->share('self_auth_video_allusers',$self_auth_video_allusers);
                }
                return $next($request);
            });
        }
    }
}
