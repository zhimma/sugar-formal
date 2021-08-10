<?php

namespace App\Http\Middleware;

use Gate;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\DB;

class Admin
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
        if (Gate::allows('admin', $this->auth->user())) {
            return $next($request);
        }
        if (Gate::allows('juniorAdmin', $this->auth->user())) {
            //檢查權限
            $getPermission=DB::table('role_user')->where('user_id',$this->auth->user()->id)->where('role_id',3)->first();
            $checkPermission = DB::table('admin_menu_items')->whereIn('id',explode(',',$getPermission->item_permission))->where('route_path',$_SERVER['REQUEST_URI'])->first();
            if(!is_null($checkPermission) || $_SERVER['REQUEST_URI']='/admin/manager'){
                return $next($request);
            }
        }

        return response()->view('errors.401', [], 401);
    }
}
