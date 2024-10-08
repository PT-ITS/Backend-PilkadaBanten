<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jenis_barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis_barang',
        'jumlah',
        'bantuan_tokoh_id',
    ];
}
