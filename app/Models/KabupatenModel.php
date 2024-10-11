<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KabupatenModel extends Model
{
    use HasFactory;

    protected $fillable = [
        "provinsi_id",
        "name",
        "id_jenis",
    ];
}
