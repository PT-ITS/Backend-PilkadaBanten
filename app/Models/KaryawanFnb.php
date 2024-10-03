<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaryawanFnb extends Model
{
    use HasFactory;

    protected $fillable = [
        'karyawan_id',
        'fnb_id',
        'created_at'
    ];
}
