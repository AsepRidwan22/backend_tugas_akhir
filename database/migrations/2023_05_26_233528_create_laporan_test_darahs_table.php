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
        Schema::create('laporan_test_darahs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->float('hemoglobin');
            $table->float('kolesterol_total');
            $table->float('kolesterol_hdl');
            $table->float('kolesterol_ldl');
            $table->float('kolesterol_trigliserida');
            $table->integer('leukosit');
            $table->date('tanggal_periksa');
            $table->float('tekanan_darah_sistolik');
            $table->float('tekanan_darah_diastolik');
            $table->float('glukosa_darah');
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
        Schema::dropIfExists('laporan_test_datahs');
    }
};
