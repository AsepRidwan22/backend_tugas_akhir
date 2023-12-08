<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Responses\PrettyJsonResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class SettingController extends Controller
{
    public function changeVerficationEnabled(Request $request): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $user->verification_code_enabled = $request->verification_code_enabled;
            $user->save();
            if (!$user->verification_code_enabled) {
                $user->verification_code = null;
            }
        } catch (JWTException $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Maaf terjadi kesalahan yang tidak terduga'], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Status verification code berhasil diubah']);
    }
}
