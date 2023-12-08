<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konsultasi extends Model
{
    use HasFactory, Uuid;
    protected $fillable = [
        'id_pasien', 'id_dokter', 'started_at', 'ended_at',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'id_pasien');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'id_dokter');
    }
}
