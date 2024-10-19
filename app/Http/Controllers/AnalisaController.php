<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterKabupaten;
use App\Models\MasterKecamatan;
use App\Models\MasterKelurahan;
use App\Models\MasterDataWarga;
use App\Models\MasterDataDpt;


class AnalisaController extends Controller
{
    public function listKabupaten()
    {
        // Ambil kabupaten berdasarkan provinsi_id tertentu
        $listKabupaten = MasterKabupaten::get();

        // Buat array untuk menampung hasil akhir
        $result = [];

        foreach ($listKabupaten as $kabupaten) {
            // Ambil kecamatan berdasarkan kabupaten_id
            $listKecamatan = MasterKecamatan::where('kabupaten_id', $kabupaten->id)->get();

            $totalDpt = MasterDataDpt::where('id_kabupaten', $kabupaten->id)->count();

            $totalWarga = MasterDataWarga::where('id_kabupaten', $kabupaten->id)->count();

            // Menghitung jumlah warga berdasarkan jenis kelamin
            // $jumlahPria = MasterDataWarga::where('id_kabupaten', $kabupaten->id)
            //     ->where('jenis_kelamin', 'L')
            //     ->count();

            // $jumlahWanita = MasterDataWarga::where('id_kabupaten', $kabupaten->id)
            //     ->where('jenis_kelamin', 'P')
            //     ->count();

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
                'jumlah_kelurahan' => $jumlahKelurahan,
                'total_dpt' => $totalDpt,
                'total_warga' => $totalWarga,
                // 'jumlah_pria' => $jumlahPria,
                // 'jumlah_wanita' => $jumlahWanita,
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

            $totalDpt = MasterDataDpt::where('id_kecamatan', $kecamatan->id)->count();

            $totalWarga = MasterDataWarga::where('id_kecamatan', $kecamatan->id)->count();

            // Menghitung jumlah warga berdasarkan jenis kelamin
            // $jumlahPria = MasterDataWarga::where('id_kecamatan', $kecamatan->id)
            //     ->where('jenis_kelamin', 'L')
            //     ->count();

            // $jumlahWanita = MasterDataWarga::where('id_kecamatan', $kecamatan->id)
            //     ->where('jenis_kelamin', 'P')
            //     ->count();

            // Tambahkan data ke array
            $kecamatanData[] = [
                'id' => $kecamatan->id,
                'nama' => $kecamatan->name,
                'jumlah_kelurahan' => $jumlahKelurahan,
                'total_dpt' => $totalDpt,
                'total_warga' => $totalWarga,
                // 'jumlah_pria' => $jumlahPria,
                // 'jumlah_wanita' => $jumlahWanita,
            ];
        }

        // Kembalikan response JSON
        return response()->json([
            'id' => '1',
            'data' => $kecamatanData
        ]);
    }

    public function listKelurahanByKecamatan($id)
    {
        // Ambil semua kelurahan berdasarkan kecamatan_id
        $listKelurahan = MasterKelurahan::where('kecamatan_id', $id)->get();

        // Siapkan array untuk menampung hasil
        $kelurahanData = [];

        // Looping melalui kelurahan
        foreach ($listKelurahan as $kelurahan) {
            $totalDpt = MasterDataDpt::where('id_kelurahan', $kelurahan->id)->count();

            $totalWarga = MasterDataWarga::where('id_kelurahan', $kelurahan->id)->count();

            // Menghitung jumlah warga berdasarkan jenis kelamin
            // $jumlahPria = MasterDataWarga::where('id_kelurahan', $kelurahan->id)
            //     ->where('jenis_kelamin', 'L')
            //     ->count();

            // $jumlahWanita = MasterDataWarga::where('id_kelurahan', $kelurahan->id)
            //     ->where('jenis_kelamin', 'P')
            //     ->count();

            // Tambahkan data kelurahan ke array
            $kelurahanData[] = [
                'id' => $kelurahan->id,
                'nama' => $kelurahan->name,
                'jenis' => $kelurahan->id_jenis,
                'total_dpt' => $totalDpt,
                'total_warga' => $totalWarga,
                // 'jumlah_pria' => $jumlahPria,
                // 'jumlah_wanita' => $jumlahWanita,
            ];
        }

        // Kembalikan response JSON
        return response()->json([
            'id' => '1',
            'data' => $kelurahanData
        ]);
    }
}
