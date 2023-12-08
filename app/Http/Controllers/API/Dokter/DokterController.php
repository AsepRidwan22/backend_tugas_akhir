<?php

namespace App\Http\Controllers\API\Dokter;

use App\Http\Controllers\Controller;
use App\Http\Responses\PrettyJsonResponse;
use App\Models\IdentitasPasien;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Token;

class DokterController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return new PrettyJsonResponse(['success' => false, 'message' => 'Kredensial tidak valid'], 400);
            }
        } catch (JWTException $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Token gagal dibuat'], 500);
        }

        $user = JWTAuth::user();
        $user->load(['role']);
        if ($user->role->nama != "Dokter") {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Akses Terlalang'], 403);
        }

        $data = JWTAuth::decode(new Token($token))->toArray();
        return new PrettyJsonResponse(['success' => true, 'message' => 'Pengguna berhasil masuk', 'access_token' => $token, 'expires_at' => $data['exp']]);
    }

    //buatkan function untuk menampilkan list identitas pasien
    public function listIdentitasPasien(): JsonResponse
    {
        $user = JWTAuth::user();
        $user->load(['role']);
        if ($user->role->nama != "Dokter") {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Akses Terlalang'], 403);
        }

        $identitas = IdentitasPasien::get();

        $formattedData = $identitas->map(function ($identitasPasien) {
            return [
                'id' => $identitasPasien->id,
                'id_user' => $identitasPasien->id_user,
                'email' => $identitasPasien->user->email, // Access email from the "User" model
                'username' => $identitasPasien->user->username, // Access username from the "User" model
                'nama' => $identitasPasien->nama,
                'tanggal_lahir' => $identitasPasien->tanggal_lahir,
                'alamat' => $identitasPasien->alamat,
                'telepon' => $identitasPasien->telepon,
                'jenis_kelamin' => $identitasPasien->jenis_kelamin,
                'golongan_darah' => $identitasPasien->golongan_darah,
                'foto' => $identitasPasien->foto,
            ];
        });
        return new PrettyJsonResponse(['success' => true, 'message' => 'Berhasil menampilkan data', 'data' => $formattedData]);
    }

    public function getKesehatan($id)
    {
        try {
            $cek = JWTAuth::user();
            $cek->load(['role']);
            if ($cek->role->nama != "Dokter") {
                return new PrettyJsonResponse(['success' => false, 'message' => 'Akses Terlalang'], 403);
            }

            $user = User::where('id', $id)->first();
            $identitas = $user->identitasPasien;
            // dd($identitas);
            $kesehatan = $identitas->kesehatanPasien;

            $data['berat_badan'] = $kesehatan->berat_badan;
            // dd($data['berat_badan']);
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
            // dd($kesehatan);
            if (!$identitas) {
                return new PrettyJsonResponse(['success' => false, 'message' => 'Data Identitas Pasien tidak ditemukan'], 404);
            }
            if (!$kesehatan) {
                return new PrettyJsonResponse(['success' => false, 'message' => 'Data Kesehatan Pasien tidak ditemukan'], 500);
            }
            return new PrettyJsonResponse(['success' => false, 'message' => $e], 500);
        }
    }

    private function calculateAge($tanggalLahir): string
    {
        $today = Carbon::today();
        $umur = $tanggalLahir->diffInYears($today);

        return $umur;
    }
}
