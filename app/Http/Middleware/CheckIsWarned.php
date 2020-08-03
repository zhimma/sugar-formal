<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use App\Models\UserMeta;

class CheckIsWarned
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

//        dd($user);
        $auth_status = 0;
        if($user->isPhoneAuth()==1){
            $auth_status = 1;
        }

        if($user->meta_()->isWarned == 1){
            if($auth_status==1 && !$user->isAdminWarned()){
                //取消警示
                UserMeta::where('user_id',$user->id)->update(['isWarned'=>0]);
            }
//            dd($user->meta_()->isWarned);
            return $next($request);
        }

        if($user->meta_()->isWarned == 0 && $user->WarnedScore() >=10 && $auth_status==0 && $user->id != 1049){
            //加入警示
            UserMeta::where('user_id',$user->id)->update(['isWarned'=>1]);

//            return $next($request);
        }


        return $next($request);
    }
}
