<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bantuan_relawan extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis_bantuan',
        'tanggal',
        'sasaran',
        'harga_satuan',
        'jumlah_penerima',
        'jumlah_bantuan',
        'relawan_id'
    ];
}
