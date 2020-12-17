<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Log;
use App\Models\Vip;
use App\Models\User;

class VipCheck
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
        $now = new \DateTime(\Carbon\Carbon::now()->toDateTimeString());
        $user = $this->auth->user();
        // Check VIP expiry.
        if ($user->isVip()) {
            $userVIP = $user->getVipData(true);
            $expiry = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $userVIP->expiry);
            if($now > $expiry && $userVIP->expiry != '0000-00-00 00:00:00'){
                \App\Models\VipLog::addToLog($user->id, 'Expired, system auto cancellation.', 'XXXXXXXXX', 0, 0);
                $userVIP->removeVIP();
            }
        }

        // 傳換性別為男生時取消原女免費VIP
        if($user->isFreeVip()){
            if($user->engroup == 1) {
                $userVIP = $user->getVipData(true);
                $userVIP->removeVIP();
            }
        }
        return $next($request);
    }
}
