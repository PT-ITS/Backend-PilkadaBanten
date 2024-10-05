<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBantuanMasyarakatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bantuan_masyarakats', function (Blueprint $table) {
            $table->id();
            $table->string('pelaksana');
            $table->date('tanggal');
            $table->string('lokasi');
            $table->string('jenis_barang');
            $table->string('jumlah_yang_disalurkan');
            $table->string('sasaran_penerima');
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
        Schema::dropIfExists('bantuan_masyarakats');
    }
}
