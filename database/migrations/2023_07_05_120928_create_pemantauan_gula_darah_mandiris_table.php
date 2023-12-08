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
        Schema::create('pemantauan_gula_darah_mandiris', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('tanggal');
            $table->integer('prandial_pagi');
            $table->integer('prandial_malam');
            $table->integer('basal_malam');
            $table->integer('sebelum_makan');
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
        Schema::dropIfExists('pemantauan_gula_darah_mandiris');
    }
};
