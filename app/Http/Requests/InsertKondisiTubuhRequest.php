<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Responses\PrettyJsonResponse;

class InsertKondisiTubuhRequest extends FormRequest
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
            'tanggal' => ['required', 'date'],
            'bmi' => ['required', 'integer'],
            'lingkar_pinggang' => ['required', 'integer'],
            'tekanan_darah' => ['required', 'integer'],
            'gula_darah' => ['required', 'integer'],
            'jam_makan_terakhir' => ['required', 'integer'],
            'filament_test' => ['required', 'integer'],
            'abl' => ['required', 'integer'],
            // 'id_identitas_pasien' => ['required', 'exists:identitas_pasiens,id', 'regex:/^[\w-]*$/'],
        ];
    }

    public function failedValidation(Validator $validator): JsonResponse
    {
        throw new HttpResponseException(new PrettyJsonResponse(['success' => false, 'message' => 'Data yang diberikan tidak valid', 'errors' => $validator->errors()], 400));
    }
}
