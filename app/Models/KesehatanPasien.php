<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KesehatanPasien extends Model
{
    use HasFactory, Uuid;
    // protected $fillable = ['lama_diabetes', 'tinggi_badan', 'berat_badan', 'lingkar_lengan_atas', 'lingkar_perut', 'riwayat_keluarga_diabetes', 'perokok', 'aktivitas_fisik', 'tekanan_darah_sistolik', 'tekanan_darah_diastolik', 'path_rekam_medis', 'id_identitas_pasien'];
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
