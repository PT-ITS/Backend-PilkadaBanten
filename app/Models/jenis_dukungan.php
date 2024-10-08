<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jenis_dukungan extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis_dukungan',
        'jumlah',
        'dukungan_tokoh_id',
    ];
}
