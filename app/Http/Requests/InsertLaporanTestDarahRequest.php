<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Responses\PrettyJsonResponse;

class InsertLaporanTestDarahRequest extends FormRequest
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
            'tanggal_periksa' => ['required', 'date'],
            'hemoglobin' => ['required', 'numeric'],
            'kolesterol_total' => ['required', 'numeric'],
            'kolesterol_hdl' => ['required', 'numeric'],
            'kolesterol_ldl' => ['required', 'numeric'],
            'kolesterol_trigliserida' => ['required', 'numeric'],
            'leukosit' => ['required', 'integer'],
            'tekanan_darah_sistolik' => ['required', 'numeric'],
            'tekanan_darah_diastolik' => ['required', 'numeric'],
            'glukosa_darah' => ['required', 'numeric'],
        ];
    }

    public function failedValidation(Validator $validator): JsonResponse
    {
        throw new HttpResponseException(new PrettyJsonResponse(['success' => false, 'message' => 'Data yang diberikan tidak valid', 'errors' => $validator->errors()], 400));
    }
}
