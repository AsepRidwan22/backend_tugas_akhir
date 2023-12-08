<?php

namespace App\Http\Controllers\API\Pasien;

use App\Http\Controllers\Controller;
use App\Http\Requests\InsertRencanaPenangananRequest;
use App\Models\RencanaPenangananKomplikasi;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Responses\PrettyJsonResponse;
use Exception;
use Illuminate\Http\JsonResponse;

class RencanaPenangananKomplikasiController extends Controller
{
    //buatkan fungsi insert
    public function insert(InsertRencanaPenangananRequest $request): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $user->load(['identitasPasien']);
            $data = $request->all();
            $data['id_identitas_pasien'] = $user->identitasPasien->id;
            $rencanaPenangananKomplikasi = RencanaPenangananKomplikasi::create($data);
        } catch (Exception $e) {
            // dd($e);
            return new PrettyJsonResponse(['success' => false, 'message' => $e], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Data Rencana Penanganan Komplikasi Berhasil Dibuat', 'data' => $rencanaPenangananKomplikasi], 201);
    }

    //buatkan function update
    public function update(InsertRencanaPenangananRequest $request, $id): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $data = $request->all();
            $rencanaPenangananKomplikasi = RencanaPenangananKomplikasi::where([['id_identitas_pasien', $user->identitasPasien->id], ['id', $id]])->first();
            $rencanaPenangananKomplikasi->update($data);
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => $e], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Rencana Penanganan Komplikasi Berhasil Diubah', 'data' => $rencanaPenangananKomplikasi]);
    }

    //buatkan function delete
    public function delete($id): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $rencanaPenangananKomplikasi = RencanaPenangananKomplikasi::where([['id_identitas_pasien', $user->identitasPasien->id], ['id', $id]])->first();
            $rencanaPenangananKomplikasi->delete();
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => $e], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Rencana Penanganan Komplikasi Berhasil Dihapus', 'data' => $rencanaPenangananKomplikasi]);
    }

    //buatkan function index untuk menampilkan semua data
    public function index(): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $user->load(['identitasPasien']);
            $rencanaPenangananKomplikasi = RencanaPenangananKomplikasi::where('id_identitas_pasien', $user->identitasPasien->id)->get();
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => $e], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'data' => $rencanaPenangananKomplikasi]);
    }
}
