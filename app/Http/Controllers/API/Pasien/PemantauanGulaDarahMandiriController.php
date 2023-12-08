<?php

namespace App\Http\Controllers\API\Pasien;

use App\Http\Controllers\Controller;
use App\Http\Requests\InsertPemantauanGulaDarahMandiriRequest;
use App\Models\PemantauanGulaDarahMandiri;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Responses\PrettyJsonResponse;
use Exception;
use Illuminate\Http\JsonResponse;

class PemantauanGulaDarahMandiriController extends Controller
{
    public function insert(InsertPemantauanGulaDarahMandiriRequest $request): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $user->load(['identitasPasien']);
            $data = $request->all();
            $data['id_identitas_pasien'] = $user->identitasPasien->id;
            $pemantauanGulaDarahMandiri = PemantauanGulaDarahMandiri::create($data);
        } catch (Exception $e) {
            // dd($e);
            return new PrettyJsonResponse(['success' => false, 'message' => $e], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Data Kondisi Tubuh Berhasil Dibuat', 'data' => $pemantauanGulaDarahMandiri], 201);
    }

    public function update(InsertPemantauanGulaDarahMandiriRequest $request, $id): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $data = $request->all();
            $pemantauanGulaDarahMandiri = PemantauanGulaDarahMandiri::where([['id_identitas_pasien', $user->identitasPasien->id], ['id', $id]])->first();
            $pemantauanGulaDarahMandiri->update($data);
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => $e], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Kondisi Tubuh Berhasil Diubah', 'data' => $pemantauanGulaDarahMandiri]);
    }

    public function delete($id): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $pemantauanGulaDarahMandiri = PemantauanGulaDarahMandiri::where([['id_identitas_pasien', $user->identitasPasien->id], ['id', $id]])->first();
            $pemantauanGulaDarahMandiri->delete();
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => $e], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Kesehatan Berhasil Dihapus', 'data' => $pemantauanGulaDarahMandiri]);
    }

    //buatkan function index untuk menampilkan seluruh data
    public function index(): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $user->load(['identitasPasien']);
            $pemantauanGulaDarahMandiri = PemantauanGulaDarahMandiri::where('id_identitas_pasien', $user->identitasPasien->id)->get();
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => $e], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'data' => $pemantauanGulaDarahMandiri]);
    }
}
