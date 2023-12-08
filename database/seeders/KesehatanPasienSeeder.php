<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KesehatanPasienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    // $table->date('lama_diabetes');
    // $table->integer('tinggi_badan');
    // $table->integer('berat_badan');
    // $table->integer('lingkar_lengan_atas');
    // $table->integer('lingkar_perut');
    // $table->integer('tekanan_darah_sistolik');
    // $table->integer('tekanan_darah_diastolik');
    // $table->string('path_rekam_medis');
    // $table->foreignUuid('id_identitas_pasien')->references('id')->on('identitas_pasiens')
    //     ->onDelete('cascade')
    //     ->onUpdate('cascade');
    public function run()
    {
        DB::table('kesehatan_pasiens')->insert([
            [
                'id' => Str::uuid()->toString(),
                'lama_diabetes' => '2021-01-01',
                'tinggi_badan' => 170,
                'berat_badan' => 70,
                // 'lingkar_lengan_atas' => 30,
                // 'lingkar_perut' => 30,
                'lingkar_lengan_atas' => 30,
                'lingkar_perut' => 30,
                'riwayat_keluarga_diabetes' => true,
                'perokok' => true,
                // 'riwayat_stroke' => true,
                'tekanan_darah_sistolik' => 120,
                'tekanan_darah_diastolik' => 80,
                'path_rekam_medis' => 'rekam_medis1.jpg',
                'id_identitas_pasien' => '984add0a-fa2a-4b38-93db-799e0b7605b6',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
        ]);
    }
}
