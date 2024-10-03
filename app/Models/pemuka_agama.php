<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pemuka_agama extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'pesantren',
        'alamat',
        'kota',
        'kec',
        'kel',
        'support',
    ];
}
