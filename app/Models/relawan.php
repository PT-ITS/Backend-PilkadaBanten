<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class relawan extends Model
{
    use HasFactory;

    protected $fillable = [
        "nik",
        "nama",
        "alamat",
        "kota",
        "kec",
        "kel",
        "rt_rw",
        "jumlah_data"
    ];
}
