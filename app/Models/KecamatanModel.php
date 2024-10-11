<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KecamatanModel extends Model
{
    use HasFactory;

    protected $fillable = [
        "kabupaten_id",
        "name",
    ];
}
