<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionChecker
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param $type
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $type)
    {
        if ($type == 'status_check') {
            // To logout all disabled account
            if ($request->user()->status != 'enabled') {
                Auth::logout();
                return redirect('/login');
            }
        } else {
            if ($request->user()->role == 'employee') {
                // This is used to check the page that is absolutely for admin and it is not available in user permission table
                if ($type == 'admin') {
                    abort('403', '您没有权限浏览此页面');
                } else {
                    // Return false also if the type doesn't match any column of user_permissions table
                    if (!$request->user()->hasAccess($type)) {
                        abort('403', '您没有权限浏览此页面');
                    }
                }
            }
        }

        return $next($request);
    }
}
