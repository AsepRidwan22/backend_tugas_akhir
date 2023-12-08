<?php

namespace App\Http\Controllers\API\Pasien;

use App\Http\Controllers\Controller;
use App\Http\Requests\InsertLaporanTestDarahRequest;
use App\Models\LaporanTestDarah;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Responses\PrettyJsonResponse;
use Exception;
use Illuminate\Http\JsonResponse;

class LaporanTestDarahController extends Controller
{
    public function insert(InsertLaporanTestDarahRequest $request): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $user->load(['identitasPasien']);
            $data = $request->all();
            $data['id_identitas_pasien'] = $user->identitasPasien->id;
            $laporanTestDarah = LaporanTestDarah::create($data);
        } catch (Exception $e) {
            // dd($e);
            return new PrettyJsonResponse(['success' => false, 'message' => $e], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Data Kondisi Tubuh Berhasil Dibuat', 'data' => $laporanTestDarah], 201);
    }

    public function update(InsertLaporanTestDarahRequest $request, $id): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $data = $request->all();
            $laporanTestDarah = LaporanTestDarah::where([['id_identitas_pasien', $user->identitasPasien->id], ['id', $id]])->first();
            $laporanTestDarah->update($data);
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => $e], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Kondisi Tubuh Berhasil Diubah', 'data' => $laporanTestDarah]);
    }

    public function delete($id): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $laporanTestDarah = LaporanTestDarah::where([['id_identitas_pasien', $user->identitasPasien->id], ['id', $id]])->first();
            $laporanTestDarah->delete();
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => $e], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Kesehatan Berhasil Dihapus', 'data' => $laporanTestDarah]);
    }

    //buatkan function untuk get seluruh data laporan test darah
    public function index(): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $laporanTestDarah = LaporanTestDarah::where('id_identitas_pasien', $user->identitasPasien->id)->get();
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => $e], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'data' => $laporanTestDarah]);
    }
}
