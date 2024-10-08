<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class data_rw extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik',
        'nama',
        'kota',
        'kec',
        'kel',
        'rw',
        'support',
        'relawan_id'
    ];
}
