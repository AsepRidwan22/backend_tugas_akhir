<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LogoutRequest;
use App\Http\Responses\PrettyJsonResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LogoutControllers extends Controller
{
    public function performs(): JsonResponse
    {
        try {
            $user = Auth::user(); // Get the authenticated user
            $token = JWTAuth::fromUser($user); // Generate token from the user

            JWTAuth::invalidate($token);
            return new PrettyJsonResponse(['success' => true, 'message' => 'Logout berhasil']);
        } catch (JWTException $exception) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Maaf terjadi kesalahan yang tidak terduga'], 500);
        }
    }
}
