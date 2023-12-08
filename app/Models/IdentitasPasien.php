<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdentitasPasien extends Model
{
    use HasFactory, Uuid;

    // protected $fillable = [
    //     'nama', 'tanggal_lahir', 'alamat', 'telepon', 'id_user'
    // ];
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function kesehatanPasien()
    {
        return $this->hasOne(KesehatanPasien::class, 'id_identitas_pasien', 'id');
    }

    public function kondisiTubuh()
    {
        return $this->hasOne(KondisiTubuh::class, 'id_identitas_pasien', 'id');
    }
}
