<?php

namespace App\Http\Controllers\API\Pasien;

use App\Http\Controllers\Controller;
use App\Http\Requests\InsertRencanaMakanRequest;
use App\Models\RencanaMakan;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Responses\PrettyJsonResponse;
use Exception;
use Illuminate\Http\JsonResponse;

class RencanaMakanController extends Controller
{
    //buatkan fungsi insert
    public function insert(InsertRencanaMakanRequest $request): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $user->load(['identitasPasien']);
            $data = $request->all();
            $data['id_identitas_pasien'] = $user->identitasPasien->id;
            $rencanaMakan = RencanaMakan::create($data);
        } catch (Exception $e) {
            // dd($e);
            return new PrettyJsonResponse(['success' => false, 'message' => $e], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Data Rencana Makan Berhasil Dibuat', 'data' => $rencanaMakan], 201);
    }

    //buatkan function update
    public function update(InsertRencanaMakanRequest $request, $id): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $user->load(['identitasPasien']);
            $data = $request->all();
            // dd($user->identitasPasien->id);
            $rencanaMakan = RencanaMakan::where([['id_identitas_pasien', $user->identitasPasien->id], ['id', $id]])->first();
            $rencanaMakan->update($data);
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => $e], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Rencana Makan Berhasil Diubah', 'data' => $rencanaMakan]);
    }

    //buatkan function delete
    public function delete($id): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $rencanaMakan = RencanaMakan::where([['id_identitas_pasien', $user->identitasPasien->id], ['id', $id]])->first();
            $rencanaMakan->delete();
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => $e], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Rencana Makan Berhasil Dihapus', 'data' => $rencanaMakan]);
    }

    //buatkan function index untuk menampilkan semua data
    public function index(): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $user->load(['identitasPasien']);
            $rencanaMakan = RencanaMakan::where('id_identitas_pasien', $user->identitasPasien->id)->get();
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => $e], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'data' => $rencanaMakan]);
    }
}
