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

    // Relasi hasMany ke data_pemilih
    public function data_pemilih()
    {
        return $this->hasMany(data_pemilih::class, 'relawan_id');
    }
}
