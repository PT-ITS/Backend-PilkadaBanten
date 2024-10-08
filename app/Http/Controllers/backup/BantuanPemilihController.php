<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ImportBantuanPemilih;
use App\Models\bantuan_pemilih;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class BantuanPemilihController extends Controller
{
    public function listBantuanPemilihByRelawan($id)
    {
        $dataBantuanPemilih = bantuan_pemilih::where('relawan_id', $id)->get();

        return response()->json(['message' => 'get data bantuan pemilih success', 'data' => $dataBantuanPemilih], 200);
    }

    public function importBantuanPemilihByRelawan(Request $request, $id)
    {
        // Validate the incoming request, including the Excel file and relawan data
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }

        try {
            // Import bantuan pemilih from Excel file
            $importedBantuanPemilih = Excel::toArray(new ImportBantuanPemilih, $request->file('file'))[0];

            // Initialize counters for success and failure tracking
            $successDataCount = 0;
            $failDataCount = 0;
            // $failedRows = [];
            // $errors = [];

            // Loop through the imported data
            foreach ($importedBantuanPemilih as $index => $data) {
                if (is_numeric($data['tanggal'])) {
                    $tanggal = Date::excelToDateTimeObject($data['tanggal'])->format('Y-m-d');
                } else {
                    $tanggal = $data['tanggal']; // Jika sudah format tanggal, gunakan apa adanya
                }

                $dataBantuanPemilih = new bantuan_pemilih([
                    'jenis_bantuan' => $data['jenis_bantuan'],
                    'tanggal' => $tanggal,
                    'jumlah' => $data['jumlah'],
                    'relawan_id' => $id
                ]);
                // Coba simpan data ke database
                if ($dataBantuanPemilih->save()) {
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

    public function updateBantuanPemilih(Request $request, $id)
    {
        $validateData = $request->validate([
            'jenis_bantuan' => 'required|string',
            'tanggal' => 'required|date',
            'jumlah' => 'required|integer',
        ]);

        DB::beginTransaction();
        try {
            $dataBantuanPemilih = bantuan_pemilih::find($id);
            $dataBantuanPemilih->jenis_bantuan = $validateData['jenis_bantuan'];
            $dataBantuanPemilih->tanggal = $validateData['tanggal'];
            $dataBantuanPemilih->jumlah = $validateData['jumlah'];
            $dataBantuanPemilih->save();

            DB::commit();
            return response()->json(['message' => 'update data bantuan pemilih success'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function deleteBantuanPemilih($id)
    {
        try {
            $dataBantuanPemilih = bantuan_pemilih::find($id);

            if ($dataBantuanPemilih) {
                $dataBantuanPemilih->delete();
                return response()->json(['message' => 'delete data bantuan pemilih success'], 200);
            }
            return response()->json(['message' => 'data bantuan pemilih tidak ditemukan'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }
}
