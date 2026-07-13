<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnforceAbsoluteSessionLifetime
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->hasSession() || !Auth::check()) {
            return $next($request);
        }

        $authenticatedAt = (int) $request->session()->get('authenticated_at', 0);
        $absoluteLifetimeSeconds = max((int) config('session.absolute_lifetime', 1440), 1) * 60;
        $hasExpired = $authenticatedAt <= 0 || (time() - $authenticatedAt) >= $absoluteLifetimeSeconds;

        if (!$hasExpired) {
            return $next($request);
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Your session has expired. Please log in again.'], 401);
        }

        return redirect()->route('login')->with('status', 'Your session has expired. Please log in again.');
    }
}
