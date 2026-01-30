<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PageLoadTestToken
{
    public function handle($request, Closure $next)
    {
        $token = $request->query('__plt');

        if ($token) {
            $cacheKey = 'page_load_test_token:' . $token;
            $payload = Cache::pull($cacheKey);
            if ($payload && isset($payload['user_id'])) {
                Auth::onceUsingId($payload['user_id']);
            }
        }

        return $next($request);
    }
}
