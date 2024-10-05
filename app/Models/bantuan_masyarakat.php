<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bantuan_masyarakat extends Model
{
    use HasFactory;

    protected $fillable = [
        'pelaksana',
        'tanggal',
        'lokasi',
        'jenis_barang',
        'jumlah_yang_disalurkan',
        'sasaran_penerima',
        'penanggung_jawab',
    ];
}
