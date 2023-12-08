<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IdentitasDokterRequest extends FormRequest
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
            'nama' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'foto' => 'required|string',
            'telepon' => 'required|regex:/^\d{10,15}$/',
        ];
    }
}
