<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class VerifyCode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = JWTAuth::user();

        if ($user && $user->verification_code_enabled) {
            $pin = $request->header('X-Verification-Pin');

            if (!$pin) {
                return response()->json(['message' => 'Verification PIN is required'], 400);
            }

            if (!Hash::check($pin, $user->verification_code)) {
                return response()->json(['message' => 'Invalid verification PIN'], 401);
            }
        }

        return $next($request);
    }
}
