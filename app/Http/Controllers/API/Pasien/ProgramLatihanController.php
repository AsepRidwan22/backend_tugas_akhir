<?php

namespace App\Http\Controllers\API\Pasien;

use App\Http\Controllers\Controller;
use App\Http\Requests\InsertProgramLatihanRequest;
use App\Models\ProgramLatihan;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Responses\PrettyJsonResponse;
use Exception;
use Illuminate\Http\JsonResponse;

class ProgramLatihanController extends Controller
{
    //buatkan fungsi insert
    public function insert(InsertProgramLatihanRequest $request): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $user->load(['identitasPasien']);
            $data = $request->all();
            $data['id_identitas_pasien'] = $user->identitasPasien->id;
            $programLatihan = ProgramLatihan::create($data);
        } catch (Exception $e) {
            // dd($e);
            return new PrettyJsonResponse(['success' => false, 'message' => $e], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Data Program Latihan Berhasil Dibuat', 'data' => $programLatihan], 201);
    }

    //buatkan function update
    public function update(InsertProgramLatihanRequest $request, $id): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $data = $request->all();
            $programLatihan = ProgramLatihan::where([['id_identitas_pasien', $user->identitasPasien->id], ['id', $id]])->first();
            $programLatihan->update($data);
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => $e], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Program Latihan Berhasil Diubah', 'data' => $programLatihan]);
    }

    //buatkan function delete
    public function delete($id): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $programLatihan = ProgramLatihan::where([['id_identitas_pasien', $user->identitasPasien->id], ['id', $id]])->first();
            $programLatihan->delete();
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => $e], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Program Latihan Berhasil Dihapus', 'data' => $programLatihan]);
    }

    //buatkan function index untuk menampilkan semua data
    public function index(): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $programLatihan = ProgramLatihan::where('id_identitas_pasien', $user->identitasPasien->id)->get();
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => $e], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'data' => $programLatihan]);
    }
}
