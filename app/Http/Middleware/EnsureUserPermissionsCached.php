<?php

namespace App\Http\Middleware;

use App\UserPermission;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

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
        // Only run if user is authenticated
        if (Auth::check()) {
            $userId = Auth::user()->id;

            // Check if permissions are cached
            if (!Cache::has('user' . $userId)) {
                // Cache is empty, fetch and cache permissions
                $permissions = UserPermission::where('user_id', $userId)->get();
                Cache::forever('user' . $userId, $permissions);
            }
        }

        return $next($request);
    }
}
