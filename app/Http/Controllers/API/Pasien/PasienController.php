<?php

namespace App\Http\Controllers\API\Pasien;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterStoreRequest;
use App\Http\Responses\PrettyJsonResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Token;
use Exception;

class PasienController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return new PrettyJsonResponse(['success' => false, 'message' => 'Kredensial tidak valid'], 400);
            }
        } catch (JWTException $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Token gagal dibuat'], 500);
        }

        $user = JWTAuth::user();
        $user->load(['role']);
        if ($user->role->nama != "Pasien") {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Akses Terlalang'], 403);
        }

        $data = JWTAuth::decode(new Token($token))->toArray();
        return new PrettyJsonResponse(['success' => true, 'message' => 'Pengguna berhasil masuk', 'access_token' => $token, 'expires_at' => $data['exp']]);
    }

    public function register(RegisterStoreRequest $request)
    {
        try {
            $user = User::create([
                'username' => $request->get('username'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
                'id_role' => 3
            ]);
            $token = JWTAuth::fromUser($user);
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Maaf terjadi kesalahan yang tidak terduga'], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Registrasi pasien berhasil', 'data' => compact('user', 'token')], 201);
    }

    // public function getAuthenticatedUser()
    // {
    //     try {

    //         if (! $user = JWTAuth::parseToken()->authenticate()) {
    //             return response()->json(['user_not_found'], 404);
    //         }

    //     } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

    //         return response()->json(['token_expired'], $e->getStatusCode());

    //     } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

    //         return response()->json(['token_invalid'], $e->getStatusCode());

    //     } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

    //         return response()->json(['token_absent'], $e->getStatusCode());

    //     }

    //     return response()->json(compact('user'));
    // }
}
