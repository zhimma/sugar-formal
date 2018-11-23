<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Log;
use App\Models\Vip;

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
        //If the member is male VIP, then check his expiry date.
        if ($this->auth->user()->isVip() && $this->auth->user()->engroup == 1) {
            $userVIP = Vip::where('member_id', $this->auth->user()->id)->get()->first();
            if($now > $userVIP->expiry && $userVIP->expiry != '0000-00-00 00:00:00'){
                $userVIP->removeVIP();
            }
        }
        return $next($request);
    }
}
