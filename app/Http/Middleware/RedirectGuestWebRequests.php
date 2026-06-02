<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectGuestWebRequests
{
    public function handle(Request $request, Closure $next)
    {
        if ($this->shouldAllowGuestAccess($request) || Auth::check()) {
            return $next($request);
        }

        return redirect()->route('login');
    }

    protected function shouldAllowGuestAccess(Request $request): bool
    {
        return $request->is('login')
            || $request->is('register')
            || $request->is('password')
            || $request->is('password/*')
            || $request->is('login-attempt');
    }
}
