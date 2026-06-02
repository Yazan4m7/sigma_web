<?php

namespace App\Http\Middleware;

use App\Support\PageBenchmark;
use Closure;

class ViewCasesListMiddleware
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
        $isBenchmarking = PageBenchmark::bootIfActive($request);

        if (Auth()->check()) {
            $permissions = safe_permissions();
            if (Auth()->user()->is_admin || ($permissions && $permissions->contains('permission_id', 103))) {
                if ($isBenchmarking) {
                    PageBenchmark::mark($request, 'route-permission.view-cases-list.pass', [
                        'permissions_count' => $permissions ? $permissions->count() : 0,
                    ]);
                }

                return $next($request);
            }else{
                return abort(403, "Insufficient Privileges, You don't have the permission to view cases list, Contact Administrator");
            }
        }else{

            return abort(403, "You're not logged in");

        }
    }
}
