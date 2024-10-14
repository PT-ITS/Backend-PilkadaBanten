<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterKabupaten extends Model
{
    use HasFactory;

    protected $fillable = [
        "provinsi_id",
        "name",
    ];

    public function wargas()
    {
        return $this->hasMany(MasterDataWarga::class, 'id_kabupaten', 'id');
    }
}
