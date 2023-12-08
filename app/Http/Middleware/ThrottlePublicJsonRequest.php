<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ThrottlePublicJsonRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $maxAttempts = 60, $decaySeconds = 1, $prefix = '')
    {
        $key = $prefix . '|' . $request->ip();

        if (!RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            RateLimiter::hit($key, $decaySeconds);
            return $next($request);
        }

        $retryAfter = RateLimiter::availableIn($key);

        return response()->json([
            'message' => 'Too Many Attempts.',
            'retry_after' => $retryAfter
        ], Response::HTTP_TOO_MANY_REQUESTS);
    }
}
