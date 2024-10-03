<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hiburan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nib',
        'namaHiburan',
        'resiko',
        'skalaUsaha',
        'alamat',
        'koordinat',
        'namaPj',
        'emailPj',
        'passwordPj',
        'nikPj',
        'pendidikanPj',
        'teleponPj',
        'wargaNegaraPj',
        'status',
        'surveyor_id',
        'pj_id',
        'created_at'
    ];
}
