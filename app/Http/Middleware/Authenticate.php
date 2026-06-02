<?php

namespace App\Http\Middleware;

use App\Support\PageBenchmark;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
class Authenticate extends Middleware
{

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $isBenchmarking = PageBenchmark::bootIfActive($request);

        if ($isBenchmarking) {
            PageBenchmark::mark($request, 'route-auth.enter', [
                'guards' => $guards,
            ]);
        }

        $user = $this->auth->user();

        if (!$user) {
            return redirect("/login");
        }

        if ($isBenchmarking) {
            PageBenchmark::mark($request, 'route-auth.user-resolved', [
                'user_id' => $user->id,
            ]);
        }

        $response = $next($request);

        if ($isBenchmarking) {
            PageBenchmark::mark($request, 'route-auth.exit');
        }

        return $response;
    }


    protected function redirectTo($request)
    {
        if (! $request->expectsJson() || !Auth()->check() ) {
            return redirect("/login");
        }
    }
}
