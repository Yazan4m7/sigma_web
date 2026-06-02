<?php

namespace App\Http\Middleware;

use App\Support\AuthenticatedUserCache;
use Closure;
use Illuminate\Support\Facades\Auth;

class PrimeAuthenticatedUserFromCache
{
    public function handle($request, Closure $next)
    {
        if (trim($request->path(), '/') !== 'operations-dashboard' || !$request->hasSession()) {
            return $next($request);
        }

        $guard = Auth::guard();

        if (!method_exists($guard, 'getName') || $guard->hasUser()) {
            return $next($request);
        }

        $userId = $request->session()->get($guard->getName());

        if ($userId) {
            $user = AuthenticatedUserCache::get((int) $userId);

            if ($user) {
                $guard->setUser($user);
            }
        }

        return $next($request);
    }
}
