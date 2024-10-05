<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJenisDukungansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jenis_dukungans', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_dukungan');
            $table->string('jumlah');
            $table->foreignId('dukungan_tokoh_id')->constrained('dukungan_tokohs')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('jenis_dukungans');
    }
}
