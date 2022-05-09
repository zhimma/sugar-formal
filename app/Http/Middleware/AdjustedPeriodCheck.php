<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserProvisionalVariables;
use Illuminate\Contracts\Auth\Factory as Auth;

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
        //檢查女會員是否第三次登入有一次修改包養關係機會
        if($user->engroup == 2)
        {
            $user_provisional_variables = UserProvisionalVariables::where('user_id', $user->id)->first();
            if($user_provisional_variables)
            {
                if($user_provisional_variables->login_time_of_adjusted_period >= 3 && $user_provisional_variables->has_adjusted_period_first_time == 0 && (!$request->session()->has('first_exchange_period_modify_next_time')))
                {
                    return redirect('/dashboard/account_exchange_period');
                }
            } 
        }
        return $next($request);
    }
}
