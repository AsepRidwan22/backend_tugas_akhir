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
        Schema::create('target_pengetahuans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('masalah_kebutuhan');
            $table->string('target_tujuan');
            $table->date('batasan_waktu');
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
        Schema::dropIfExists('target_pengetahuans');
    }
};
