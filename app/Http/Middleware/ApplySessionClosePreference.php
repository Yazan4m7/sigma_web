<?php

namespace App\Http\Middleware;

use Closure;

class ApplySessionClosePreference
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($request->hasSession()) {
            config([
                'session.expire_on_close' => (bool) $request->session()->get('expire_on_close', false),
            ]);
        }

        return $response;
    }
}
