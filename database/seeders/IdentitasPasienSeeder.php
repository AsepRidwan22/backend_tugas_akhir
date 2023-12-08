<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class IdentitasPasienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('identitas_pasiens')->insert([
            [
                'id' => Str::uuid()->toString(),
                'id_user' => '680adc73-676d-40db-ba83-9939ec7aa802',
                'nama' => 'Pasien 1',
                'tanggal_lahir' => '1999-01-01',
                'alamat' => 'Jl. Pasien 1',
                'telepon' => '081234567890',
                'jenis_kelamin' => 'Laki-laki',
                'golongan_darah' => 'A',
                'foto' => 'pasien1.jpg',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
        ]);
    }
}
