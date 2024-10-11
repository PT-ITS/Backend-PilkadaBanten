<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterKabupaten;
use App\Models\MasterKecamatan;
use App\Models\MasterKelurahan;

class LokasiController extends Controller
{
    // list kab, kec, kel
    public function listKabupaten()
    {
        $dataKabupaten = MasterKabupaten::get();

        return response()->json([
            'id' => '1',
            'data' => $dataKabupaten
        ]);
    }

    public function listKecamatan()
    {
        $dataKecamatan = MasterKecamatan::get();

        return response()->json([
            'id' => '1',
            'data' => $dataKecamatan
        ]);
    }

    public function listKelurahan()
    {
        $dataKelurahan = MasterKelurahan::get();

        return response()->json([
            'id' => '1',
            'data' => $dataKelurahan
        ]);
    }
}
