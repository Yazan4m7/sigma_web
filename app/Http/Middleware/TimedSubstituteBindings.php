<?php

namespace App\Http\Middleware;

use App\Support\PageBenchmark;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Routing\Middleware\SubstituteBindings;

class TimedSubstituteBindings extends SubstituteBindings
{
    public function handle($request, Closure $next)
    {
        $isBenchmarking = PageBenchmark::bootIfActive($request);

        $start = microtime(true);

        try {
            $route = $request->route();

            $explicitStart = microtime(true);
            $this->router->substituteBindings($route);
            $explicitEnd = microtime(true);

            $implicitStart = microtime(true);
            $this->router->substituteImplicitBindings($route);
            $implicitEnd = microtime(true);
        } catch (ModelNotFoundException $exception) {
            if (isset($route) && $route->getMissing()) {
                return $route->getMissing()($request, $exception);
            }

            throw $exception;
        }

        $beforeNext = microtime(true);

        if ($isBenchmarking) {
            PageBenchmark::mark($request, 'middleware.substitute-bindings.complete', [
                'explicit_ms' => round(($explicitEnd - $explicitStart) * 1000, 2),
                'implicit_ms' => round(($implicitEnd - $implicitStart) * 1000, 2),
                'total_before_next_ms' => round(($beforeNext - $start) * 1000, 2),
                'route_name' => optional($route)->getName(),
            ]);
        }

        $response = $next($request);

        if ($isBenchmarking) {
            PageBenchmark::mark($request, 'middleware.substitute-bindings.exit', [
                'downstream_ms' => round((microtime(true) - $beforeNext) * 1000, 2),
            ]);
        }

        return $response;
    }
}
