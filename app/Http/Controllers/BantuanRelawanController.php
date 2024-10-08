<?php

namespace App\Http\Controllers;

use App\Exports\BantuanRelawanExport;
use Illuminate\Http\Request;
use App\Imports\ImportBantuanRelawan;
use App\Models\bantuan_relawan;
use App\Models\data_pemilih;
use App\Models\data_rt;
use App\Models\data_rw;
use App\Models\pemuka_agama;
use App\Models\relawan;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class BantuanRelawanController extends Controller
{
    public function infoJumlahJenisBantuan()
    {
        // Ambil semua data relawan
        $dataRelawan = relawan::get();

        // Inisialisasi array untuk menyimpan hasil
        $result = [];

        // Looping data relawan untuk menghitung jumlah bantuan terkait
        foreach ($dataRelawan as $relawan) {
            $id = $relawan->id; // Ambil id relawan
            // Hstikeritung jumlah bantuan berdasarkan kategori sasaran
            $uangBantuan = bantuan_relawan::where('relawan_id', $id)->where('jenis_bantuan', 'uang')->count();
            $kaosBantuan = bantuan_relawan::where('relawan_id', $id)->where('jenis_bantuan', 'kaos')->count();
            $stikerBantuan = bantuan_relawan::where('relawan_id', $id)->where('jenis_bantuan', 'stiker')->count();
            $berasBantuan = bantuan_relawan::where('relawan_id', $id)->where('jenis_bantuan', 'beras')->count();
            $spandukBantuan = bantuan_relawan::where('relawan_id', $id)->where('jenis_bantuan', 'spanduk')->count();
            $kerudungBantuan = bantuan_relawan::where('relawan_id', $id)->where('jenis_bantuan', 'kerudung')->count();

            // Masukkan data relawan dan jumlah bantuan ke dalam array hasil
            $result[] = [
                'id' => $id,
                'nama' => $relawan->nama,
                'alamat' => $relawan->alamat,
                'kota' => $relawan->kota,
                'kec' => $relawan->kec,
                'kel' => $relawan->kel,
                'bantuan_uang' => $uangBantuan,
                'bantuan_kaos' => $kaosBantuan,
                'bantuan_stiker' => $stikerBantuan,
                'bantuan_beras' => $berasBantuan,
                'bantuan_spanduk' => $spandukBantuan,
                'bantuan_kerudung' => $kerudungBantuan,
            ];
        }

        // Kembalikan hasil sebagai response JSON
        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }

    public function infoJumlahSasaranBantuan()
    {
        // Ambil semua data relawan
        $dataRelawan = relawan::get();

        // Inisialisasi array untuk menyimpan hasil
        $result = [];

        // Looping data relawan untuk menghitung jumlah bantuan terkait
        foreach ($dataRelawan as $relawan) {
            $id = $relawan->id; // Ambil id relawan

            // Hitung jumlah bantuan berdasarkan kategori sasaran
            $jumlahSemuaBantuan = bantuan_relawan::where('relawan_id', $id)->count();
            $jumlahBantuanWarga = bantuan_relawan::where('relawan_id', $id)->where('sasaran', 'warga')->count();
            $jumlahBantuanRw = bantuan_relawan::where('relawan_id', $id)->where('sasaran', 'ketua rw')->count();
            $jumlahBantuanRt = bantuan_relawan::where('relawan_id', $id)->where('sasaran', 'ketua rt')->count();
            $jumlahBantuanPemukaAgama = bantuan_relawan::where('relawan_id', $id)->where('sasaran', 'pemuka agama')->count();

            // Masukkan data relawan dan jumlah bantuan ke dalam array hasil
            $result[] = [
                'id' => $id,
                'nama' => $relawan->nama,
                'alamat' => $relawan->alamat,
                'kota' => $relawan->kota,
                'kec' => $relawan->kec,
                'kel' => $relawan->kel,
                'jumlah_semua_bantuan' => $jumlahSemuaBantuan,
                'jumlah_bantuan_warga' => $jumlahBantuanWarga,
                'jumlah_bantuan_rw' => $jumlahBantuanRw,
                'jumlah_bantuan_rt' => $jumlahBantuanRt,
                'jumlah_bantuan_pemuka_agama' => $jumlahBantuanPemukaAgama,
            ];
        }

        // Kembalikan hasil sebagai response JSON
        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }

    public function exportAllBantuanRelawan()
    {
        // Fetch relawan data with related bantuan_relawans
        $relawans = Relawan::with('bantuanRelawans')->get();

        return Excel::download(new BantuanRelawanExport($relawans), 'data_bantuan_relawan_export.xlsx');
    }

    public function exportBantuanRelawanByRelawan($id)
    {
        // Fetch relawan data with related bantuan_relawans
        $relawans = Relawan::with('bantuanRelawans')
            ->where('relawans.id', $id)
            ->get();

        return Excel::download(new BantuanRelawanExport($relawans), 'data_bantuan_relawan_export.xlsx');
    }

    public function infoBantuanByRelawan($id)
    {
        $jumlahPenerimaBantuanPemilih = data_pemilih::where('relawan_id', $id)->count();
        $jumlahPenerimaBantuanRt = data_rt::where('relawan_id', $id)->count();
        $jumlahPenerimaBantuanRw = data_rw::where('relawan_id', $id)->count();
        $jumlahPenerimaBantuanPemukaAgama = pemuka_agama::where('relawan_id', $id)->count();


        return response()->json([
            'id' => '1',
            'data' => [
                'jumlahWargaPenerimaBantuan' => $jumlahPenerimaBantuanPemilih,
                'jumlahRtPenerimaBantuan' => $jumlahPenerimaBantuanRt,
                'jumlahRwPenerimaBantuan' => $jumlahPenerimaBantuanRw,
                'jumlahPemukaAgamaPenerimaBantuan' => $jumlahPenerimaBantuanPemukaAgama,
            ]
        ]);
    }

    public function createBantuanByRelawan(Request $request)
    {
        try {
            $dataValidate = $request->validate([
                'jenis_bantuan' => 'required',
                'tanggal' => 'required',
                'sasaran' => 'required',
                'harga_satuan' => 'required',
                'jumlah_penerima' => 'required',
                'jumlah_bantuan' => 'required',
                'relawan_id' => 'required',
            ]);

            $bantuanRelawan = new bantuan_relawan();
            $bantuanRelawan->jenis_bantuan = $dataValidate['jenis_bantuan'];
            $bantuanRelawan->tanggal = $dataValidate['tanggal'];
            $bantuanRelawan->sasaran = $dataValidate['sasaran'];
            $bantuanRelawan->harga_satuan = $dataValidate['harga_satuan'];
            $bantuanRelawan->jumlah_penerima = $dataValidate['jumlah_penerima'];
            $bantuanRelawan->jumlah_bantuan = $dataValidate['jumlah_bantuan'];
            $bantuanRelawan->relawan_id = $dataValidate['relawan_id'];
            $bantuanRelawan->save();

            return response()->json(['id' => '1', 'data' => 'data bantuan berhasil disimpan']);
        } catch (\Throwable $th) {
            return response()->json(['id' => '0', 'data' => 'data bantuan gagal disimpan', 'message' => $th->getMessage()]);
        }
    }

    public function listBantuanRelawanByRelawan($id)
    {
        $dataBantuanRelawan = bantuan_relawan::where('relawan_id', $id)->get();

        return response()->json(['message' => 'get data bantuan relawan success', 'data' => $dataBantuanRelawan], 200);
    }

    public function importBantuanRelawanByRelawan(Request $request)
    {
        // Validate the incoming request, including the Excel file and relawan data
        $validator = Validator::make($request->all(), [
            'relawan_id' => 'required',
            'file' => 'required|mimes:xlsx,xls',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }

        try {
            // Import bantuan relawan from Excel file
            $importedBantuanRelawan = Excel::toArray(new ImportBantuanRelawan, $request->file('file'))[0];

            // Initialize counters for success and failure tracking
            $successDataCount = 0;
            $failDataCount = 0;
            // $failedRows = [];
            // $errors = [];

            // Loop through the imported data
            foreach ($importedBantuanRelawan as $index => $data) {
                if (is_numeric($data['tanggal'])) {
                    $tanggal = Date::excelToDateTimeObject($data['tanggal'])->format('Y-m-d');
                } else {
                    $tanggal = $data['tanggal']; // Jika sudah format tanggal, gunakan apa adanya
                }

                $dataBantuanRelawan = new bantuan_relawan([
                    'jenis_bantuan' => $data['jenis_bantuan'],
                    'tanggal' => $tanggal,
                    'sasaran' => $data['sasaran'],
                    'harga_satuan' => $data['harga_satuan'],
                    'jumlah_penerima' => $data['jumlah_penerima'],
                    'jumlah_bantuan' => $data['jumlah_bantuan'],
                    'relawan_id' => $request->relawan_id
                ]);
                // Coba simpan data ke database
                if ($dataBantuanRelawan->save()) {
                    // Jika berhasil, tambahkan ke hitungan data yang berhasil
                    $successDataCount++;
                } else {
                    // Jika gagal disimpan ke database, tambahkan ke hitungan data yang gagal
                    $failDataCount++;
                }
            }

            // Return success response with summary of import
            return response()->json([
                'message' => 'Data imported successfully.',
                'success_data_count' => $successDataCount,
                'fail_data_count' => $failDataCount,
            ]);
        } catch (\Exception $e) {
            // Return error response if the process fails
            return response()->json(['message' => 'An error occurred while importing data.', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateBantuanRelawan(Request $request, $id)
    {
        $validateData = $request->validate([
            'jenis_bantuan' => 'required|string',
            'tanggal' => 'required|date',
            'harga_satuan' => 'required|integer',
            'jumlah_penerima' => 'required|integer',
            'jumlah_bantuan' => 'required|integer',
        ]);

        DB::beginTransaction();
        try {
            $dataBantuanRelawan = bantuan_relawan::find($id);
            $dataBantuanRelawan->jenis_bantuan = $validateData['jenis_bantuan'];
            $dataBantuanRelawan->tanggal = $validateData['tanggal'];
            $dataBantuanRelawan->harga_satuan = $validateData['harga_satuan'];
            $dataBantuanRelawan->jumlah_penerima = $validateData['jumlah_penerima'];
            $dataBantuanRelawan->jumlah_bantuan = $validateData['jumlah_bantuan'];
            $dataBantuanRelawan->save();

            DB::commit();
            return response()->json(['message' => 'update data bantuan relawan success'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function deleteBantuanRelawan($id)
    {
        try {
            $dataBantuanRelawan = bantuan_relawan::find($id);

            if ($dataBantuanRelawan) {
                $dataBantuanRelawan->delete();
                return response()->json(['message' => 'delete data bantuan relawan success'], 200);
            }
            return response()->json(['message' => 'data bantuan relawan tidak ditemukan'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }
}
