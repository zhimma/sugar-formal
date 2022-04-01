<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class CheckDiscussPermissions
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
        if ($this->auth->user()->engroup!==1) {
            return redirect('/dashboard');
        }
        if (!$this->auth->user()->meta->isAllSet( $this->auth->user()->engroup )){
            return redirect('/dashboard')->with('message', '請寫上基本資料。');
        }
        if($this->auth->user()->access_posts==1){
            return redirect('/dashboard/personalPage')->with('message', '您好，您目前被站方限制使用討論區，若有疑問請點右下角，聯繫站長Line@');
        }
        if (!$this->auth->user()->isVip()) {
            return redirect('/dashboard/new_vip')->with('message', '請先升級VIP，加入即時討論');

        }

        return $next($request);
    }
}
