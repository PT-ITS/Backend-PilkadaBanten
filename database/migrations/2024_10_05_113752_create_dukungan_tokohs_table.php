<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDukunganTokohsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dukungan_tokohs', function (Blueprint $table) {
            $table->id();
            $table->string('pelaksana');
            $table->date('tanggal');
            $table->string('lokasi');
            $table->string('sasaran');
            $table->string('penanggung_jawab');
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
        Schema::dropIfExists('dukungan_tokohs');
    }
}
