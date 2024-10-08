<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dukungan_tokoh extends Model
{
    use HasFactory;

    protected $fillable = [
        'pelaksana',
        'tanggal',
        'lokasi',
        'sasaran',
        'penanggung_jawab',
    ];
}
