<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBantuanRelawansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bantuan_relawans', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_bantuan');
            $table->date('tanggal');
            $table->string('sasaran');
            $table->bigInteger('harga_satuan');
            $table->integer('jumlah_penerima');
            $table->integer('jumlah_bantuan');
            $table->foreignId('relawan_id')->constrained('relawans')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('bantuan_relawans');
    }
}
