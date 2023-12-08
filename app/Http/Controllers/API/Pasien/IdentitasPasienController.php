<?php

namespace App\Http\Controllers\API\Pasien;

use App\Http\Controllers\Controller;
use App\Http\Requests\IdentitasPasienRequest;
use App\Models\IdentitasPasien;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Responses\PrettyJsonResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;


class IdentitasPasienController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $data = IdentitasPasien::where('id_user', $user->id)->first();
            $data['email'] = $user->email;
            $data['username'] = $user->username;

            if (!$data) {
                return new PrettyJsonResponse(['success' => false, 'message' => 'Data identitas tidak ditemukan'], 404);
            }

            return new PrettyJsonResponse(['success' => true, 'message' => 'Data ditemukan', 'data' => $data]);
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Maaf terjadi kesalahan yang tidak terduga'], 500);
        }
    }


    public function store(IdentitasPasienRequest $request): JsonResponse
    {
        try {
            $user = JWTAuth::user();

            // Cek apakah user sudah memiliki identitas
            if ($user->identitasPasien) {
                return new PrettyJsonResponse(['success' => false, 'message' => 'Anda hanya diperbolehkan memiliki satu identitas'], 400);
            }

            $data = $request->all();

            // Simpan foto jika ada dalam request
            if ($request->has('foto') != null) {
                $base64Image = $request->input('foto');
                $folderPath = 'public/images'; // Ganti dengan folder penyimpanan yang sesuai di dalam storage
                $extension = 'jpg'; // Ekstensi default, atau ambil ekstensi dari base64 jika ada
                $fileName = Str::random(40) . '.' . $extension; // Generate nama file unik
                saveBase64Image($base64Image, $folderPath, $fileName);
                // $domain = env('APP_URL');
                $domain = 'http://192.168.1.22:8000/';
                $data['foto'] =  'storage/images/' . $fileName;
            }

            $data['id_user'] = $user->id;
            $identitas = IdentitasPasien::create($data);
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => $e], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Identitas Berhasil Dibuat', 'data' => $identitas], 201);
    }


    public function update(IdentitasPasienRequest $request): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $data = $request->all();
            $identitas = IdentitasPasien::where('id_user', $user->id)->first();

            if ($identitas->foto != null) {
                File::delete(public_path($identitas->foto));
            }

            if ($request->has('foto')) {
                $base64Image = $request->input('foto');
                $folderPath = 'public/images'; // Ganti dengan folder penyimpanan yang sesuai di dalam storage
                $extension = 'jpg'; // Ekstensi default, atau ambil ekstensi dari base64 jika ada
                $fileName = Str::random(40) . '.' . $extension; // Generate nama file unik
                saveBase64Image($base64Image, $folderPath, $fileName);
                // $domain = env('APP_URL');
                $domain = 'http://192.168.1.22:8000/';
                $data['foto'] =  'storage/images/' . $fileName;
            }

            $identitas->update($data);

            return new PrettyJsonResponse(['success' => true, 'message' => 'Identitas Berhasil Diubah', 'data' => $identitas]);
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => $e], 500);
        }
    }
}

function saveBase64Image($base64String, $folderPath, $fileName)
{
    $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64String));
    $path = $folderPath . '/' . $fileName;
    Storage::put($path, $imageData);
    return $path;
}
