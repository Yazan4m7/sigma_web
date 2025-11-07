<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class ViewDevicesMonitorMiddleware
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
        if (Auth()->check()) {
            $permissions = safe_permissions();
            if (Auth()->user()->is_admin || ($permissions && $permissions->contains('permission_id', 133))) {
                return $next($request);
            }else{
                return abort(403, "Insufficient Privileges, You don't have the permission to view devices monitor, Contact Admin");
            }
        }else{
            return abort(403, "You're not logged in");
        }
    }
}
