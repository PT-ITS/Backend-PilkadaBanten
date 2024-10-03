<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class data_pemilih extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik',
        'nama',
        'alamat',
        'kota',
        'kec',
        'desa_kel',
        'rt_rw',
        'tps',
        'relawan_id'
    ];

    public function relawan()
    {
        return $this->belongsTo(Relawan::class);
    }
}
