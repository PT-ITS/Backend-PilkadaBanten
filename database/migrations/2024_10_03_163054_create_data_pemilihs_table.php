<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataPemilihsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_pemilihs', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique();
            $table->string('nama');
            $table->string('alamat');
            $table->string('kota');
            $table->string('kec');
            $table->string('desa_kel');
            $table->string('rt_rw');
            $table->string('tps');
            $table->unsignedBigInteger('relawan_id');
            $table->timestamps();
            
            $table->foreign('relawan_id')->references('id')->on('relawan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_pemilihs');
    }
}
