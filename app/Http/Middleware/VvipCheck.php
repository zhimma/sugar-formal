<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use App\Models\VvipInfo;

class VvipCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle(Request $request, Closure $next)
    {
        $user = $this->auth->user();

        if($user->isVVIP())
        {
            if(!(VvipInfo::where('user_id', $user->id)->first() ?? false))
            {
                return Redirect('/dashboard/vvipInfo');
            }
            elseif(!VvipInfo::where('user_id', $user->id)->first()->has_writed)
            {
                return Redirect('/dashboard/vvipInfo');
            }
        }

        return $next($request);
    }
}
