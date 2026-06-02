<?php

namespace App\Http\Middleware;

use App\Support\PageBenchmark;
use App\Support\UserPermissionsCache;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class EnsureUserPermissionsCached
{
    /**
     * Handle an incoming request.
     * Ensures user permissions are always cached before proceeding.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $isBenchmarking = PageBenchmark::bootIfActive($request);

        // Only run if user is authenticated
        if (Auth::check()) {
            $userId = Auth::id();
            $requestPermissions = $request->attributes->get('user_permissions');

            if ($requestPermissions instanceof Collection) {
                if ($isBenchmarking) {
                    PageBenchmark::mark($request, 'middleware.permissions-cache.request-hit', [
                        'user_id' => $userId,
                        'permission_count' => $requestPermissions->count(),
                    ]);
                }

                return $next($request);
            }

            $permissions = UserPermissionsCache::get($userId);
            $request->attributes->set('user_permissions', $permissions);

            if ($isBenchmarking) {
                PageBenchmark::mark($request, 'middleware.permissions-cache.store', [
                    'user_id' => $userId,
                    'permission_count' => $permissions instanceof Collection ? $permissions->count() : 0,
                ]);
            }
        } elseif ($isBenchmarking) {
            PageBenchmark::mark($request, 'middleware.permissions-cache.skipped', [
                'reason' => 'guest',
            ]);
        }

        return $next($request);
    }
}
