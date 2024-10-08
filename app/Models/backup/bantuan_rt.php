<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bantuan_rt extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis_bantuan',
        'tanggal',
        'jumlah',
        'rt_id'
    ];
}
