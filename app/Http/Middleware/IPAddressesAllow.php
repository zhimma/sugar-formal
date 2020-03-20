<?php

namespace App\Http\Middleware;

use Closure;

class IPAddressesAllow
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $go=0;
        $result = file_get_contents(public_path("ip2.txt"));
        $result = explode(PHP_EOL, $result);
        $ipaddress = sprintf("%u", ip2long($request->ip()));
        foreach ($result as &$r){
            $r = explode(" , ", $r);

            $lowerIp = sprintf("%u", ip2long($r[0]));
            $higherIp = sprintf("%u", ip2long($r[1]));

            //echo ($lowerIp <= $ipaddress) ."<br>";
            if ( $ipaddress <= $higherIp && $ipaddress >= $lowerIp ) {
                    $go = 1;
                    break;
                }
        }
        if ($go != 1) {
            abort(403);
        }
        return $next($request);
    }
}
