<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Responses\PrettyJsonResponse;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;

class UsersControllers extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $user = JWTAuth::user();

            if ($user->role['nama'] == 'Pasien') {
                $data = User::where('id_role', '2')->get();
            } else {
                $data = User::where('id_role', '3')->get();
            }

            return new PrettyJsonResponse(['success' => true, 'message' => 'Data ditemukan', 'data' => $data]);
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Maaf terjadi kesalahan yang tidak terduga'], 500);
        }
    }
}
