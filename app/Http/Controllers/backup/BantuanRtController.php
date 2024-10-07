<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ImportBantuanRt;
use App\Models\bantuan_rt;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class BantuanRtController extends Controller
{
    public function listBantuanRtByRt($id)
    {
        $dataBantuanRt = bantuan_rt::where('rt_id', $id)->get();

        return response()->json(['message' => 'get data bantuan rt success', 'data' => $dataBantuanRt], 200);
    }

    public function importBantuanRtByRt(Request $request, $id)
    {
        // Validate the incoming request, including the Excel file and relawan data
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }

        try {
            // Import bantuan rt from Excel file
            $importedBantuanRt = Excel::toArray(new ImportBantuanRt, $request->file('file'))[0];

            // Initialize counters for success and failure tracking
            $successDataCount = 0;
            $failDataCount = 0;
            // $failedRows = [];
            // $errors = [];

            // Loop through the imported data
            foreach ($importedBantuanRt as $index => $data) {
                if (is_numeric($data['tanggal'])) {
                    $tanggal = Date::excelToDateTimeObject($data['tanggal'])->format('Y-m-d');
                } else {
                    $tanggal = $data['tanggal']; // Jika sudah format tanggal, gunakan apa adanya
                }

                $dataBantuanRt = new bantuan_rt([
                    'jenis_bantuan' => $data['jenis_bantuan'],
                    'tanggal' => $tanggal,
                    'jumlah' => $data['jumlah'],
                    'rt_id' => $id
                ]);
                // Coba simpan data ke database
                if ($dataBantuanRt->save()) {
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

    public function updateBantuanRt(Request $request, $id)
    {
        $validateData = $request->validate([
            'jenis_bantuan' => 'required|string',
            'tanggal' => 'required|date',
            'jumlah' => 'required|integer',
        ]);

        DB::beginTransaction();
        try {
            $dataBantuanRt = bantuan_rt::find($id);
            $dataBantuanRt->jenis_bantuan = $validateData['jenis_bantuan'];
            $dataBantuanRt->tanggal = $validateData['tanggal'];
            $dataBantuanRt->jumlah = $validateData['jumlah'];
            $dataBantuanRt->save();

            DB::commit();
            return response()->json(['message' => 'update data bantuan rt success'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function deleteBantuanRt($id)
    {
        try {
            $dataBantuanRt = bantuan_rt::find($id);

            if ($dataBantuanRt) {
                $dataBantuanRt->delete();
                return response()->json(['message' => 'delete data bantuan rt success'], 200);
            }
            return response()->json(['message' => 'data bantuan rt tidak ditemukan'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }
}
