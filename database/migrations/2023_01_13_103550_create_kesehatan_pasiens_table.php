<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kesehatan_pasiens', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('lama_diabetes');
            $table->integer('tinggi_badan');
            $table->integer('berat_badan');
            $table->integer('lingkar_lengan_atas');
            $table->integer('lingkar_perut');
            $table->boolean('riwayat_keluarga_diabetes');
            $table->boolean('perokok');
            // $table->boolean('riwayat_stroke');
            $table->enum('aktivitas_fisik', ['Ringan', 'Sedang', 'Berat']);
            $table->integer('tekanan_darah_sistolik');
            $table->integer('tekanan_darah_diastolik');
            $table->string('path_rekam_medis')->nullable();
            $table->foreignUuid('id_identitas_pasien')->references('id')->on('identitas_pasiens')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kesehatan_pasiens');
    }
};
