<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaryawanHiburan extends Model
{
    use HasFactory;

    protected $fillable = [
        'karyawan_id',
        'hiburan_id',
        'created_at'
    ];
}
