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
        Schema::create('kondisi_tubuhs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('tanggal');
            $table->integer('bmi');
            $table->integer('lingkar_pinggang');
            $table->integer('tekanan_darah');
            $table->integer('gula_darah');
            $table->integer('jam_makan_terakhir');
            $table->integer('filament_test');
            $table->integer('abl');
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
        Schema::dropIfExists('kondisi_tubuhs');
    }
};
