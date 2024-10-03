<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaryawanHotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'karyawan_id',
        'hotel_id',
        'created_at'
    ];
}
