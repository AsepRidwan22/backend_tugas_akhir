<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\String\Exception\RuntimeException;

class ThrottleAuthJsonRequest
{
    /**
     * The rate limiter instance.
     *
     * @var \Illuminate\Cache\RateLimiter
     */
    protected $limiter;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Cache\RateLimiter  $limiter
     * @return void
     */
    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int|string  $maxAttempts
     * @param  int|string|null  $decaySeconds
     * @return mixed
     *
     * @throws \Illuminate\Http\Exceptions\ThrottleRequestsException
     */
    public function handle($request, Closure $next, $maxAttempts = 60, $decaySeconds = 60)
    {
        $key = $this->resolveRequestSignature($request);

        $maxAttempts = is_string($maxAttempts) ? (int) $maxAttempts : $maxAttempts;
        $decaySeconds = is_string($decaySeconds) ? (int) $decaySeconds : $decaySeconds;

        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            $retryAfter = $this->limiter->availableIn($key);
            $response = response()->json([
                'status' => 'error',
                'message' => 'Too many attempts. Please try again in ' . $retryAfter . ' seconds.'
            ], 429);
            $response->header('Retry-After', $retryAfter);

            throw new ThrottleRequestsException($response);
        }

        $this->limiter->hit($key, $decaySeconds);

        $response = $next($request);

        if ($response->status() >= 400) {
            $this->limiter->clear($key);
        }

        return $response;
    }

    /**
     * Resolve request signature.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function resolveRequestSignature($request)
    {
        if ($user = $request->user()) {
            return sha1($user->getAuthIdentifier());
        }

        if ($route = $request->route()) {
            return sha1($route->getDomain() . '|' . $request->ip());
        }

        throw new RuntimeException('Unable to generate the request signature. Route unavailable.');
    }
}
