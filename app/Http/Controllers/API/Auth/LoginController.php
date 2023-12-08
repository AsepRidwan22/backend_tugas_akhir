<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Responses\PrettyJsonResponse;
use App\Jobs\SendVerificationJob;
use App\Mail\VerifyEmail;
use App\Models\IdentitasDokter;
use App\Models\IdentitasPasien;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Token;

class LoginController extends Controller
{
    public function dokterLogin(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        // $credentials['password'] = $this->passwordDecypher($credentials['password']);

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return new PrettyJsonResponse(['success' => false, 'message' => 'Kredensial tidak valid'], 400);
            }
        } catch (JWTException $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Token gagal dibuat'], 500);
        }

        $user = JWTAuth::user();
        if (IdentitasDokter::where('id_user', $user->id)->first() != null) {
            $identitas = IdentitasDokter::where('id_user', $user->id)->first();
            $user['profile'] = $identitas->foto;
            $user['nama'] = $identitas->nama;
        }

        $user->load(['role']);
        if ($user->role->nama != "Dokter") {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Akses Terlalang'], 403);
        } else if ($user->status != 1) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Akun anda belum aktif'], 403);
        }

        if ($user->verification_code_enabled) {
            $this->sendVerificationEmail($user);
        }

        $data = JWTAuth::decode(new Token($token))->toArray();
        return new PrettyJsonResponse(['success' => true, 'message' => 'Pengguna berhasil masuk', 'data' => $user, 'access_token' => $token, 'expires_at' => $data['exp']]);
    }

    public function pasienLogin(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        // $credentials['password'] = $this->passwordDecypher($credentials['password']);
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return new PrettyJsonResponse(['success' => false, 'message' => 'Kredensial tidak valid'], 400);
            }
        } catch (JWTException $e) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Token gagal dibuat'], 500);
        }

        $user = JWTAuth::user();
        if (IdentitasPasien::where('id_user', $user->id)->first() != null) {
            $identitas = IdentitasPasien::where('id_user', $user->id)->first();
            $user['profile'] = $identitas->foto;
            $user['nama'] = $identitas->nama;
        }

        $user->load(['role']);
        if ($user->role->nama != "Pasien") {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Akses Terlalang'], 403);
        } else if ($user->status != 1) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Akun anda belum aktif'], 403);
        }

        // if ($user->verification_code_enabled) {
        //     $this->sendVerificationEmail($user);
        // }

        $data = JWTAuth::decode(new Token($token))->toArray();
        return new PrettyJsonResponse(['success' => true, 'message' => 'Pengguna berhasil masuk', 'data' => $user, 'access_token' => $token, 'expires_at' => $data['exp']]);
    }

    function sendVerificationEmail($user)
    {
        $pin = rand(100000, 999999);
        $user->verification_code = bcrypt($pin);
        $user->save();
        Mail::to($user->email)->queue(new VerifyEmail($pin));
        // SendVerificationJob::dispatch($user->email, $pin)->onQueue('emails');
    }

    function passwordDecypher($password)
    {
        $cipher = "aes-256-cbc";
        $iv = '_V98wmtPGyCf)d{[';
        $secret_key = 'n8D;}uywn)5e3Pd-2b[:5dF2Ghg9WHA{';
        $decypher_password = openssl_decrypt($password, $cipher, $secret_key, $options = 0, $iv);
        return $decypher_password;
    }
}
