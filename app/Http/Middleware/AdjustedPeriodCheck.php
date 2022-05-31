<?php

namespace App\Http\Middleware;

use App\Models\LogUserLogin;
use Closure;
use Illuminate\Http\Request;
use App\Models\UserProvisionalVariables;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Support\Facades\Log;

class AdjustedPeriodCheck
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $this->auth->user();
        //檢查女會員是否第十次登入有一次修改包養關係機會
        if($user->engroup == 2)
        {
            $user_provisional_variables = UserProvisionalVariables::where('user_id', $user->id)->first();
            $user_login_count = LogUserLogin::where('user_id', $user->id)->count();
            if($user_provisional_variables)
            {
                if($user_login_count >= 10 && $user_provisional_variables->has_adjusted_period_first_time == 0 && (!$request->session()->has('first_exchange_period_modify_next_time')))
                {
                    return redirect('/dashboard/account_exchange_period');
                }
            } 
        }
        return $next($request);
    }
}
