<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Responses\PrettyJsonResponse;

class InsertKesehatanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'lama_diabetes' => 'required|date',
            'tinggi_badan' => 'required|integer',
            'berat_badan' => 'required|integer',
            'lingkar_lengan_atas' => 'required|integer',
            'lingkar_perut' => 'required|integer',
            'riwayat_keluarga_diabetes' => 'required|boolean',
            'perokok' => 'required|boolean',
            // 'riwayat_stroke' => 'required|boolean',
            'aktivitas_fisik' => 'required|in:Ringan,Sedang,Berat',
            'tekanan_darah_sistolik' => 'required|integer',
            'tekanan_darah_diastolik' => 'required|integer',
            'path_rekam_medis' => 'nullable|string',
            // 'id_identitas_pasien' => 'required|exists:identitas_pasiens,id|regex:/^[\w-]*$/',
        ];
    }

    public function failedValidation(Validator $validator): JsonResponse
    {
        throw new HttpResponseException(new PrettyJsonResponse(['success' => false, 'message' => 'Data yang diberikan tidak valid', 'errors' => $validator->errors()], 400));
    }
}
