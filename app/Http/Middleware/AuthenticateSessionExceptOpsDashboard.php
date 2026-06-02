<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Session\Middleware\AuthenticateSession;

class AuthenticateSessionExceptOpsDashboard extends AuthenticateSession
{
    public function handle($request, Closure $next)
    {
        if (trim($request->path(), '/') === 'operations-dashboard') {
            return $next($request);
        }

        return parent::handle($request, $next);
    }
}
