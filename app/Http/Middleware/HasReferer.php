<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HasReferer
{
    /**
     * 檢查是否是用連結訪問的，如果不適的話就彈到指定url(預設 dashboard
     *
     * 使用方式為 HasReferer:listSeatch2
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $name 登記在route的name
     * @return mixed
     */
    public function handle(Request $request, Closure $next,$name = 'dashboard')
    {
        if ($request->headers->get('referer'))
            return $next($request);
        return redirect(route($name));
    }
}
