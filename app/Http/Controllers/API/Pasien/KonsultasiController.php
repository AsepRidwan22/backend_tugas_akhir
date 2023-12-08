<?php

namespace App\Http\Controllers\Api\Pasien;

use App\Http\Controllers\Controller;
use App\Http\Requests\KonsultasiRequest;
use App\Models\Konsultasi;
use App\Http\Responses\PrettyJsonResponse;
use Exception;
use Illuminate\Support\Facades\Auth;

class KonsultasiController extends Controller
{
    public function create(KonsultasiRequest $request)
    {
        try {
            $idPasien = Auth::user()->id;
            $idDokter = $request->input('id_dokter');

            // Cek apakah konsultasi antara pasien dan dokter sudah ada sebelumnya
            // $existingConsultation = Consultation::where('patient_id', $patientId)
            //     ->where('doctor_id', $doctorId)
            //     ->first();

            // if ($existingConsultation) {
            //     return response()->json([
            //         'message' => 'Konsultasi antara pasien dan dokter sudah ada',
            //     ], 400);
            // }

            Konsultasi::create([
                'id_pasien' => $idPasien,
                'id_dokter' => $idDokter,
            ]);
        } catch (Exception $th) {
            return new PrettyJsonResponse(['success' => false, 'message' => 'Maaf terjadi kesalahan yang tidak terduga'], 500);
        }

        return new PrettyJsonResponse(['success' => true, 'message' => 'Konsultasi berhasil dibuat'], 201);
    }
}
