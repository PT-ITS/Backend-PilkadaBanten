<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBantuanPemukaAgamasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bantuan_pemuka_agamas', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_bantuan');
            $table->date('tanggal');
            $table->integer('jumlah');
            $table->foreignId('pemuka_agama_id')->constrained('pemuka_agamas')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('bantuan_pemuka_agamas');
    }
}
