<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Log;
use mysql_xdevapi\Session;

class Filled
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
        if (!$this->auth->user()->meta_()->isAllSet()) {
            $collection = collect([
                (string)$this->auth->user()->meta_()->returnUnSet()
            ]);
            $request->session()->flash('errors', $collection);
            return redirect('dashboard');
        }

        return $next($request);
    }
}
