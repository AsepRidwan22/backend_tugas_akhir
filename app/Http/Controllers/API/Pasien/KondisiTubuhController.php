<?php

namespace App\Http\Controllers\API\Pasien;

use App\Http\Controllers\Controller;
use App\Http\Requests\InsertKondisiTubuhRequest;
use App\Models\KondisiTubuh;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Responses\PrettyJsonResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Models\User;

class KondisiTubuhController extends Controller
{
    public function insert(InsertKondisiTubuhRequest $request): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $user->load(['identitasPasien']);
            $data = $request->all();
            $data['id_identitas_pasien'] = $user->identitasPasien->id;
            $kondisiTubuh = KondisiTubuh::create($data);
        } catch (Exception $e) {
            // dd($e);
            return new PrettyJsonResponse(['success' => false, 'message' => $e], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Data Kondisi Tubuh Berhasil Dibuat', 'data' => $kondisiTubuh], 201);
    }

    public function update(InsertKondisiTubuhRequest $request, $id): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $data = $request->all();
            $kondisiTubuh = KondisiTubuh::where([['id_identitas_pasien', $user->identitasPasien->id], ['id', $id]])->first();
            $kondisiTubuh->update($data);
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => $e], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Kondisi Tubuh Berhasil Diubah', 'data' => $kondisiTubuh]);
    }

    public function delete($id): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $kondusiTubuh = KondisiTubuh::where([['id_identitas_pasien', $user->identitasPasien->id], ['id', $id]])->first();
            $kondusiTubuh->delete();
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => $e], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Kesehatan Berhasil Dihapus', 'data' => $kondusiTubuh]);
    }

    //buatkan function index untuk menampilkan data semua kondisi tubuh
    public function index(): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $kondisiTubuh = KondisiTubuh::where('id_identitas_pasien', $user->identitasPasien->id)->get();
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => $e], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'data' => $kondisiTubuh]);
    }
}
