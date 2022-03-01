<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use Session;
use Illuminate\Support\Facades\Log;

class SessionExpired
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    protected $timeout = 1800;

    public function __construct() {
        $this->timeout = config('session.lifetime') * 60;   //單位要是秒
    }

    public function handle(Request $request, Closure $next)
    {   
        $isLoggedIn = $request->path() != '/logout';
        if(!$request->session()->has('lastActivityTime'))
        {
            $request->session()->put('lastActivityTime', time());
        }
        elseif(time() - $request->session()->get('lastActivityTime') > $this->timeout)
        {
            $request->session()->forget('lastActivityTime');
            return redirect()->route('logout');
        }
        $isLoggedIn ? $request->session()->put('lastActivityTime', time()) : $request->session()->forget('lastActivityTime');
        
        return $next($request);
    }
}
