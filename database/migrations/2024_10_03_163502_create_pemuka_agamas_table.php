<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePemukaAgamasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemuka_agamas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('pesantren');
            $table->string('alamat');
            $table->string('kota');
            $table->string('kec');
            $table->string('kel');
            $table->enum('support', ['0', '1'])->default('0');
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
        Schema::dropIfExists('pemuka_agamas');
    }
}
