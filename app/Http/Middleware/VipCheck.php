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
        //If the member is male VIP, then check his expiry date. && $this->auth->user()->engroup == 1
        if ($this->auth->user()->isVip() ) {
            $userVIP = Vip::where('member_id', $this->auth->user()->id)->get()->first();
            $expiry = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $userVIP->expiry);
            if($now > $expiry && $userVIP->expiry != '0000-00-00 00:00:00'){
                \App\Models\VipLog::addToLog($this->auth->user()->id, 'Expired auto cancellation.', 'XXXXXXXXX', 0, 0);
                $userVIP->removeVIP();
            }
        }

        //傳換性別為男生時取消原女免費VIP
        if($this->auth->user()->isFreeVip()){
            $user = User::findById($this->auth->user()->id);
            if($user->engroup==1) {
                $userVIP = Vip::where('member_id', $this->auth->user()->id)->get()->first();
                $userVIP->removeVIP();
            }
        }
        return $next($request);
    }
}
