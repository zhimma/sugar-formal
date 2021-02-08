<?php

namespace App\Http\Controllers;

use View;

class BaseController extends Controller
{
    //
    protected $user, $user_meta, $userIsVip, $unread, $valueAddedServices;

    public function __construct() {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->user = view()->shared('user');
            $this->user_meta = view()->shared('user_meta');
            $this->userIsVip = view()->shared('isVip');
            $this->unread = view()->shared('unread');
            $this->valueAddedServices = view()->shared('valueAddedServices');

            return $next($request);
        });
    }
}
