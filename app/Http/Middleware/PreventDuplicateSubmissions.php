<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PreventDuplicateSubmissions
{
    private const CACHE_PREFIX = 'submission-lock:';
    private const LOCK_SECONDS = 8;

    /**
    * Block duplicate non-idempotent requests that share the same submission token.
    */
    public function handle(Request $request, Closure $next)
    {
        if ($this->isSafeMethod($request)) {
            return $next($request);
        }

        $token = $this->submissionToken($request);
        $cacheKey = self::CACHE_PREFIX . sha1($token);

        if (!Cache::add($cacheKey, now()->timestamp, now()->addSeconds(self::LOCK_SECONDS))) {
            return $this->duplicateResponse($request);
        }

        return $next($request);
    }

    private function submissionToken(Request $request): string
    {
        $provided = (string) $request->input('_submission_id', $request->header('X-Submission-Id'));
        if ($provided !== '') {
            return $this->userScopedToken($request, $provided);
        }

        return $this->userScopedToken(
            $request,
            implode('|', [
                $request->method(),
                $request->fullUrl(),
                $this->userIdentifier($request),
            ])
        );
    }

    private function userScopedToken(Request $request, string $token): string
    {
        $userPart = $this->userIdentifier($request);

        return $userPart . '|' . $token;
    }

    private function userIdentifier(Request $request): string
    {
        $user = $request->user();
        if ($user && method_exists($user, 'getAuthIdentifier')) {
            return (string) $user->getAuthIdentifier();
        }

        return (string) $request->session()->getId();
    }

    private function isSafeMethod(Request $request): bool
    {
        return $request->isMethodSafe();
    }

    private function duplicateResponse(Request $request)
    {
        $message = 'This action is already being processed. Please wait a moment before retrying.';

        if ($request->expectsJson()) {
            return response()->json(['message' => $message], 409);
        }

        return redirect()->back()->with('error', $message);
    }
}
