<?php

namespace App\Http\Controllers\API\Dokter;

use App\Http\Controllers\Controller;
use App\Http\Requests\IdentitasDokterRequest;
use App\Models\IdentitasDokter;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Responses\PrettyJsonResponse;
use Exception;
use Illuminate\Http\JsonResponse;

class IdentitasDokterController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $data = IdentitasDokter::where('id_user', $user->id)->first();
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Maaf terjadi kesalahan yang tidak terduga'], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Data ditemukan', 'data' => $data]);
    }

    public function store(IdentitasDokterRequest $request): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $data = $request->all();
            $data['id_user'] = $user->id;
            $identitas = IdentitasDokter::create($data);
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Maaf terjadi kesalahan yang tidak terduga'], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Identitas Berhasil Dibuat', 'data' => $identitas], 201);
    }

    public function update(IdentitasDokterRequest $request): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $data = $request->all();
            $identitas = IdentitasDokter::where('id_user', $user->id)->first();
            $identitas->update($data);
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Maaf terjadi kesalahan yang tidak terduga'], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Identitas Berhasil Diubah', 'data' => $identitas]);
    }
}
