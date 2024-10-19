<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterKabupaten;
use App\Models\MasterKecamatan;
use App\Models\MasterKelurahan;

class DptController extends Controller
{
    public function listKabupaten()
    {
        $dataKabupaten = MasterKabupaten::get();

        return response()->json(['id' => '1', 'data' => $dataKabupaten]);
    }

    public function listKecamatan($id)
    {
        $dataKecamatan = MasterKecamatan::where('kabupaten_id', $id)->get();

        return response()->json(['id' => '1', 'data' => $dataKecamatan]);
    }

    public function updateDptKab(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'dpt' => 'required',
                'target' => 'required'
            ]);
            $dataDptKab = MasterKabupaten::find($id);
            $dataDptKab->dpt = $validateData['dpt'];
            $dataDptKab->target = $validateData['target'];
            $dataDptKab->save();
            return response()->json(['id' => '1', 'data' => 'data dpt berhasil diupdate']);
        } catch (\Throwable $th) {
            return response()->json(['id' => '0', 'data' => 'data dpt gagal diupdate']);
        }
    }

    public function updateDptKec(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'dpt' => 'required',
                'target' => 'required'
            ]);
            $dataDptKec = MasterKecamatan::find($id);
            $dataDptKec->dpt = $validateData['dpt'];
            $dataDptKec->target = $validateData['target'];
            $dataDptKec->save();
            return response()->json(['id' => '1', 'data' => 'data dpt berhasil diupdate']);
        } catch (\Throwable $th) {
            return response()->json(['id' => '0', 'data' => 'data dpt gagal diupdate']);
        }
    }
}
