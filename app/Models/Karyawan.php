<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $fillable = [
        'namaKaryawan',
        // 'nikKaryawan',
        'pendidikanKaryawan',
        'jabatanKaryawan',
        'alamatKaryawan',
        'sertifikasiKaryawan',
        'wargaNegara',
        'surveyor_id',
        'jenisKelamin',
        'created_at'
    ];
}
