<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PermissionChecker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $type)
    {

        if ($request->user()->role == 'employee'){
            if(!$request->user()->hasAccess($type)){
                abort('403', 'You have no permission');
            }
        }
        return $next($request);
    }
}
