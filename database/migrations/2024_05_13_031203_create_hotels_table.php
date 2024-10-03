<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('nib');
            $table->string('namaHotel');
            $table->string('bintangHotel');
            $table->string('kamarVip');
            $table->string('kamarStandart');
            $table->string('resiko');
            $table->string('skalaUsaha');
            $table->text('alamat');
            $table->string('koordinat');
            $table->string('namaPj');
            $table->string('emailPj');
            $table->string('passwordPj');
            $table->string('nikPj');
            $table->string('pendidikanPj');
            $table->string('teleponPj');
            $table->string('wargaNegaraPj');
            $table->enum('status', [
                '0', // belum tervalidasi
                '1' // tervalidasi
            ])->default('0');
            $table->foreignId('surveyor_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('pj_id')->nullable()->constrained('users')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('hotels');
    }
}
