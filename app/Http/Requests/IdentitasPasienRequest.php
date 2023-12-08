<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Responses\PrettyJsonResponse;

class IdentitasPasienRequest extends FormRequest
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
            'nama' => ['required', 'string', 'max:255'],
            'tanggal_lahir' => ['date'],
            'alamat' => ['required', 'max:255'],
            'telepon' => ['required', 'regex:/^\d{10,15}$/'],
            'jenis_kelamin' => ['required', 'regex:/^(Laki-laki|Perempuan)$/'],
            'golongan_darah' => ['required', 'regex:/^(A|B|O|AB)[+-]$/'],
            'foto' => ['string'],
        ];
    }

    public function failedValidation(Validator $validator): JsonResponse
    {
        throw new HttpResponseException(new PrettyJsonResponse(['success' => false, 'message' => 'Data yang diberikan tidak valid', 'errors' => $validator->errors()], 400));
    }
}
