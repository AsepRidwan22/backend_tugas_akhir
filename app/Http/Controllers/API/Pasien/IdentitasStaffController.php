<?php

namespace App\Http\Controllers\API\Pasien;

use App\Http\Controllers\Controller;
use App\Http\Requests\InsertIdentitasStaffRequest;
use App\Models\IdentitasStaff;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Responses\PrettyJsonResponse;
use Exception;
use Illuminate\Http\JsonResponse;

class IdentitasStaffController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $data = IdentitasStaff::where('id_user', $user->id)->first();
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Maaf terjadi kesalahan yang tidak terduga'], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Data ditemukan', 'data' => $data]);
    }

    public function store(InsertIdentitasStaffRequest $request): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $data = $request->all();
            $data['id_user'] = $user->id;
            $identitas = IdentitasStaff::create($data);
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Maaf terjadi kesalahan yang tidak terduga'], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Identitas Berhasil Dibuat', 'data' => $identitas], 201);
    }

    public function update(InsertIdentitasStaffRequest $request): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $data = $request->all();
            $identitas = IdentitasStaff::where([['id_user', $user->id], ['id_role', 3]])->first();
            $identitas->update($data);
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Maaf terjadi kesalahan yang tidak terduga'], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Identitas Berhasil Diubah', 'data' => $identitas]);
    }
}
