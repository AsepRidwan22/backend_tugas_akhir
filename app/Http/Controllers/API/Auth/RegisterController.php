<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterPasienRequest;
use App\Http\Requests\RegisterStoreRequest;
use App\Http\Responses\PrettyJsonResponse;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class RegisterController extends Controller
{
    public function pasienRegister(RegisterStoreRequest $request): JsonResponse
    {
        try {
            $user = User::create([
                'username' => $request->get('username'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
                'id_role' => 3
            ]);
            // $user['username'] = 'udisn123';
            $token = JWTAuth::fromUser($user);
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => $e], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Registrasi pasien berhasil', 'data' => compact('user', 'token')], 201);
    }
}
