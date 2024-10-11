<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterDataWarga extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik',
        'nama',
        'jenis_kelamin',
        'alamat',
        'id_kabupaten',
        'id_kecamatan',
        'id_kelurahan',
        'pj_id',
    ];
}
