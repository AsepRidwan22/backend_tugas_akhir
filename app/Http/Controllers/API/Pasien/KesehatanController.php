<?php

namespace App\Http\Controllers\API\Pasien;

use App\Http\Controllers\Controller;
use App\Http\Requests\InsertKesehatanRequest;
use App\Models\KesehatanPasien;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;
// use Illuminate\Support\Facades\Hash;
use App\Http\Responses\PrettyJsonResponse;
use App\Models\IdentitasPasien;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class KesehatanController extends Controller
{
    public function insert(InsertKesehatanRequest $request): JsonResponse
    {
        try {
            $user = JWTAuth::user();

            // dd($user);
            $identitasPasien = $user->identitasPasien;

            // Cek apakah pasien sudah memiliki data kesehatan sebelumnya
            if ($identitasPasien->kesehatanPasien) {
                return new PrettyJsonResponse(['success' => false, 'message' => 'Data kesehatan sudah ada. Hanya diperbolehkan satu data kesehatan per pasien'], 400);
            }

            $data = $request->all();

            if ($request->has('path_rekam_medis') != null) {
                $base64Image = $request->input('path_rekam_medis');
                $folderPath = 'public/images'; // Ganti dengan folder penyimpanan yang sesuai di dalam storage
                $extension = 'jpg'; // Ekstensi default, atau ambil ekstensi dari base64 jika ada
                $fileName = Str::random(40) . '.' . $extension; // Generate nama file unik
                saveBase64Image($base64Image, $folderPath, $fileName);
                // $domain = env('APP_URL');
                $domain = 'http://192.168.1.22:8000/';
                $data['path_rekam_medis'] =  'storage/images/' . $fileName;
            }
            $data['id_identitas_pasien'] = $identitasPasien->id;

            $kesehatan = KesehatanPasien::create($data);
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => $e], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Kesehatan Berhasil Dibuat', 'data' => $kesehatan], 201);
    }

    public function get(): JsonResponse
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $user = User::where('id', $user->id)->first();
            $identitas = $user->identitasPasien;
            $kesehatan = $identitas->kesehatanPasien;

            if (!$kesehatan) {
                return new PrettyJsonResponse(['success' => false, 'message' => 'Kesehatan tidak ditemukan'], 404);
            }

            return new PrettyJsonResponse(['success' => true, 'message' => 'Data ditemukan', 'data' => $kesehatan]);
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Maaf terjadi kesalahan yang tidak terduga'], 500);
        }
    }



    //buatkan saya kode untuk update kesehatan
    public function update(InsertKesehatanRequest $request, $id): JsonResponse
    {
        // dd($id);
        try {
            $user = JWTAuth::user();
            $data = $request->all();
            $kesehatan = KesehatanPasien::where([['id_identitas_pasien', $user->identitasPasien->id], ['id', $id]])->first();
            // dd($kesehatan->path_rekam_medis);
            if ($kesehatan->path_rekam_medis != null) {
                File::delete(public_path($kesehatan->path_rekam_medis));
            }

            if ($request->has('path_rekam_medis') != null) {
                $base64Image = $request->input('path_rekam_medis');
                $folderPath = 'public/images'; // Ganti dengan folder penyimpanan yang sesuai di dalam storage
                $extension = 'jpg'; // Ekstensi default, atau ambil ekstensi dari base64 jika ada
                $fileName = Str::random(40) . '.' . $extension; // Generate nama file unik
                saveBase64Image($base64Image, $folderPath, $fileName);
                // $domain = env('APP_URL');
                $domain = 'http://192.168.1.22:8000/';
                $data['path_rekam_medis'] =  'storage/images/' . $fileName;
            }
            $kesehatan->update($data);
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => $e], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Kesehatan Berhasil Diubah', 'data' => $kesehatan]);
    }

    public function index()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $user = User::where('id', $user->id)->first();
            $identitas = $user->identitasPasien;
            $kesehatan = $identitas->kesehatanPasien;

            $data['berat_badan'] = $kesehatan->berat_badan;
            $data['tinggi_badan'] = $kesehatan->tinggi_badan;
            $data['umur'] = round($this->calculateAge(Carbon::parse($identitas->tanggal_lahir)));
            $data['bmi'] = round($kesehatan->berat_badan / (($kesehatan->tinggi_badan / 100) * ($kesehatan->tinggi_badan / 100)), 1);

            if ($data['bmi'] < 18.5) {
                $data['status'] = 'Kurus';
            } elseif ($data['bmi'] >= 18.5 && $data['bmi'] <= 24.9) {
                $data['status'] = 'Ideal';
            } elseif ($data['bmi'] >= 25.0 && $data['bmi'] <= 29.9) {
                $data['status'] = 'Gemuk';
            } elseif ($data['bmi'] >= 30.0 && $data['bmi'] <= 34.9) {
                $data['status'] = 'Obesitas I';
            } elseif ($data['bmi'] >= 35.0 && $data['bmi'] <= 39.9) {
                $data['status'] = 'Obesitas II';
            } elseif ($data['bmi'] >= 40.0) {
                $data['status'] = 'Obesitas III';
            }

            $data['bbi_min'] = round(18.5 * (($kesehatan->tinggi_badan / 100) * ($kesehatan->tinggi_badan / 100)), 0);
            $data['bbi_max'] = round(24.9 * (($kesehatan->tinggi_badan / 100) * ($kesehatan->tinggi_badan / 100)), 0);
            $data['bmi_min'] = 18.5;
            $data['bmi_max'] = 24.9;

            // Kalori Basal Berdasarkan Jenis Kelamin
            if ($identitas->jenis_kelamin == 'Laki-laki') {
                $data['kalori_basal'] = 66.5 + (13.75 * $kesehatan->berat_badan) + (5.003 * $kesehatan->tinggi_badan) - (6.755 * $identitas->usia);
            } else {
                $data['kalori_basal'] = 655.1 + (9.563 * $kesehatan->berat_badan) + (1.850 * $kesehatan->tinggi_badan) - (4.676 * $identitas->usia);
            }

            // Kalori Aktivitas Fisik
            $data['kalori_aktivitas_ringan'] = round($data['kalori_basal'] * 1.375, 0);
            $data['kalori_aktivitas_sedang'] = round($data['kalori_basal'] * 1.55, 0);
            $data['kalori_aktivitas_berat'] = round($data['kalori_basal'] * 1.725, 0);

            //tekanan darah
            $data['tekanan_darah_sistolik'] = $kesehatan->tekanan_darah_sistolik;
            $data['tekanan_darah_diastolik'] = $kesehatan->tekanan_darah_diastolik;

            // Menentukan hipertensi
            //jk tambahan
            $data['hipertensi'] = '';
            if ($kesehatan->tekanan_darah_sistolik >= 140 && $kesehatan->tekanan_darah_diastolik >= 90) {
                if ($kesehatan->usia >= 18 && $kesehatan->usia <= 65) {
                    $data['hipertensi'] = 'Anda termasuk dalam kategori Hipertensi. Dianjurkan untuk berkonsultasi dengan profesional medis.';
                } elseif ($kesehatan->usia > 65) {
                    $data['hipertensi'] = 'Anda termasuk dalam kategori Hipertensi. Dianjurkan untuk berkonsultasi dengan profesional medis, terutama pada usia lanjut.';
                } else {
                    $data['hipertensi'] = 'Anda tidak termasuk dalam kategori Hipertensi.';
                }
            } else {
                $data['hipertensi'] = 'Anda tidak termasuk dalam kategori Hipertensi.';
            }

            return new PrettyJsonResponse(['success' => true, 'message' => 'Kesehatan Berhasil diambil', 'data' => $data]);
        } catch (Exception $e) {
            if (!$identitas) {
                return new PrettyJsonResponse(['success' => false, 'message' => 'Data Identitas Pasien tidak ditemukan'], 404);
            }
            if (!$kesehatan) {
                return new PrettyJsonResponse(['success' => false, 'message' => 'Data Kesehatan Pasien tidak ditemukan'], 500);
            }
            return new PrettyJsonResponse(['success' => false, 'message' => $e], 500);
        }
    }

    public function delete($id): JsonResponse
    {
        try {
            $user = JWTAuth::user();
            $kesehatan = KesehatanPasien::where([['id_identitas_pasien', $user->identitasPasien->id], ['id', $id]])->first();
            $kesehatan->delete();
        } catch (Exception $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => $e], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Kesehatan Berhasil Dihapus', 'data' => $kesehatan]);
    }

    private function calculateAge($tanggalLahir): string
    {
        $today = Carbon::today();
        $umur = $tanggalLahir->diffInYears($today);

        return $umur;
    }
}


function saveBase64Image($base64String, $folderPath, $fileName)
{
    // Decode base64 string menjadi data gambar
    $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64String));

    // Simpan gambar ke folder tertentu dalam penyimpanan Laravel (storage)
    $path = $folderPath . '/' . $fileName;
    Storage::put($path, $imageData);

    // Alternatif: Jika Anda menggunakan package intervention/image, Anda dapat menyimpan gambar dalam format lain (misalnya JPEG) dan mengatur ukuran gambar jika diperlukan.
    // Contoh untuk menyimpan gambar dalam format JPEG dengan ukuran maksimal 800x600 pixel:
    // $image = Image::make($imageData)->widen(800)->heighten(600)->encode('jpg', 80);
    // Storage::put($path, $image);

    return $path; // Mengembalikan path gambar yang disimpan (relative terhadap storage)
}
