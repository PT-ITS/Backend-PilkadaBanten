<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterKabupaten;
use App\Models\MasterKecamatan;
use App\Models\MasterKelurahan;

class AnalisaController extends Controller
{
    public function listKabupaten()
    {
        // Ambil kabupaten berdasarkan provinsi_id tertentu
        $listKabupaten = MasterKabupaten::where('provinsi_id', '36')->get();

        // Buat array untuk menampung hasil akhir
        $result = [];

        foreach ($listKabupaten as $kabupaten) {
            // Ambil kecamatan berdasarkan kabupaten_id
            $listKecamatan = MasterKecamatan::where('kabupaten_id', $kabupaten->id)->get();
            
            // Hitung jumlah kelurahan di setiap kecamatan
            $jumlahKelurahan = 0;

            foreach ($listKecamatan as $kecamatan) {
                // Ambil kelurahan berdasarkan kecamatan_id
                $jumlahKelurahan += MasterKelurahan::where('kecamatan_id', $kecamatan->id)->count();
            }

            // Tambahkan data kabupaten dengan jumlah kecamatan dan kelurahan
            $result[] = [
                'kabupaten_id' => $kabupaten->id,
                'name' => $kabupaten->name,
                'jumlah_kecamatan' => $listKecamatan->count(),
                'jumlah_kelurahan' => $jumlahKelurahan
            ];
        }

        // Kembalikan response JSON
        return response()->json([
            'id' => '1',
            'data' => $result
        ]);
    }

    public function listKecamatanByKabupaten($id)
    {
        // Ambil semua kecamatan berdasarkan kabupaten_id
        $listKecamatan = MasterKecamatan::where('kabupaten_id', $id)->get();

        // Siapkan array untuk menampung hasil
        $kecamatanData = [];

        // Looping melalui kecamatan dan hitung jumlah kelurahan
        foreach ($listKecamatan as $kecamatan) {
            // Hitung jumlah kelurahan berdasarkan kecamatan_id
            $jumlahKelurahan = MasterKelurahan::where('kecamatan_id', $kecamatan->id)->count();

            // Tambahkan data ke array
            $kecamatanData[] = [
                'id' => $kecamatan->id,
                'nama' => $kecamatan->name,
                'jumlah_kelurahan' => $jumlahKelurahan,
            ];
        }

        // Kembalikan response JSON
        return response()->json([
            'id' => '1',
            'data' => $kecamatanData
        ]);
    }

    public function listKecamatan($id)
    {}

    public function listKelurahan($id)
    {}
}
