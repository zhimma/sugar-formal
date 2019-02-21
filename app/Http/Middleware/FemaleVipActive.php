<?php

namespace App\Http\Middleware;

use Gate;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Vip;
use Illuminate\Support\Facades\Config;

class FemaleVipActive
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
        $user = auth()->user();

        $user_last_login = Carbon::parse($user->last_login);
        $vip_record = Carbon::parse($user->vip_record);

        //if($user->engroup == 1 || $user->engroup == 2 && !Vip::status($user->id)) return $next($request);
        if($user->engroup == 1 || $user->engroup == 2 && !$user->existHeaderImage() || Vip::status($user->id)) return $next($request);

        if($vip_record->diffInSeconds(Carbon::now()) <= Config::get('social.vip.start') && !$user->isVip()) {
        }
        else if(!$user->isVip()) {
            $user->vip_record = Carbon::now();
            $user->save();
            Vip::upgrade($user->id, '1111000', '0', 0, 'OOOOOOOO', 1, 1);
            return $next($request);
        }
        else if($user->isVip() && $vip_record->diffInSeconds(Carbon::now()) <= Config::get('social.vip.free-days')) {
            return $next($request);
        }
        else if($user->isVip() && $user->isFreeVip()) {
            Vip::cancel($user->id, 1);
            $user->vip_record = Carbon::now();
            $user->save();
        }

        return $next($request);
    }
}
