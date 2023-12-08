<?php

namespace App\Http\Controllers\API\Pasien;

use App\Http\Controllers\Controller;
use App\Http\Requests\InsertTargetPengetahuanRequest;
use App\Models\TargetPengetahuan;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Responses\PrettyJsonResponse;
use Exception;
use Illuminate\Http\JsonResponse;

class TargetPengetahuanController extends Controller
{
    //buatkan fungsi insert
    public function insert(InsertTargetPengetahuanRequest $request): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $user->load(['identitasPasien']);
            $data = $request->all();
            $data['id_identitas_pasien'] = $user->identitasPasien->id;
            $targetPengetahuan = TargetPengetahuan::create($data);
        } catch (Exception $e) {
            // dd($e);
            return new PrettyJsonResponse(['success' => false, 'message' => $e], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Data Target Pengetahuan Berhasil Dibuat', 'data' => $targetPengetahuan], 201);
    }

    //buatkan function update
    public function update(InsertTargetPengetahuanRequest $request, $id): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $data = $request->all();
            $targetPengetahuan = TargetPengetahuan::where([['id_identitas_pasien', $user->identitasPasien->id], ['id', $id]])->first();
            $targetPengetahuan->update($data);
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => $e], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Target Pengetahuan Berhasil Diubah', 'data' => $targetPengetahuan]);
    }

    //buatkan function delete
    public function delete($id): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $targetPengetahuan = TargetPengetahuan::where([['id_identitas_pasien', $user->identitasPasien->id], ['id', $id]])->first();
            $targetPengetahuan->delete();
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => $e], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Target Pengetahuan Berhasil Dihapus', 'data' => $targetPengetahuan]);
    }

    //buatkan function index untuk menampilkan semua data
    public function index(): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $user->load(['identitasPasien']);
            $targetPengetahuan = TargetPengetahuan::where('id_identitas_pasien', $user->identitasPasien->id)->get();
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => $e], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'data' => $targetPengetahuan]);
    }
}
