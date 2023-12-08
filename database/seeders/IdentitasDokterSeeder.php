<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class IdentitasDokterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('identitas_dokters')->insert([
            [
                'id' => Str::uuid()->toString(),
                'nama' => 'Dokter 1',
                'tanggal_lahir' => '1999-01-01',
                'alamat' => 'Jl. Dokter 1',
                'jenis_kelamin' => 'Laki-laki',
                'foto' => 'pasien1.jpg',
                'telepon' => '081234567890',
                'id_user' => 'dd5a7aa8-b299-40c6-92c6-4bb0f59f2fee',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
        ]);
    }
}
