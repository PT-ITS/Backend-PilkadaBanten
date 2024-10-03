<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKaryawansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            $table->string('namaKaryawan');
            // $table->string('nikKaryawan');
            $table->string('pendidikanKaryawan');
            $table->string('jabatanKaryawan');
            $table->string('alamatKaryawan');
            $table->enum('sertifikasiKaryawan', [
                '0', // tidak tersertifikasi
                '1' // tersertifikasi
            ])->default('0');
            $table->string('wargaNegara');
            $table->enum('jenisKelamin', [
                '0', // perempuan
                '1' // laki-laki
            ]);
            $table->foreignId('surveyor_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('karyawans');
    }
}
