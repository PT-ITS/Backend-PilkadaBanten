<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ImportBantuanRw;
use App\Models\bantuan_rw;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class BantuanRwController extends Controller
{
    public function listBantuanRwByRw($id)
    {
        $dataBantuanRw = bantuan_rw::where('rw_id', $id)->get();

        return response()->json(['message' => 'get data bantuan rw success', 'data' => $dataBantuanRw], 200);
    }

    public function importBantuanRwByRw(Request $request, $id)
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
            $importedBantuanRw = Excel::toArray(new ImportBantuanRw, $request->file('file'))[0];

            // Initialize counters for success and failure tracking
            $successDataCount = 0;
            $failDataCount = 0;
            // $failedRows = [];
            // $errors = [];

            // Loop through the imported data
            foreach ($importedBantuanRw as $index => $data) {
                if (is_numeric($data['tanggal'])) {
                    $tanggal = Date::excelToDateTimeObject($data['tanggal'])->format('Y-m-d');
                } else {
                    $tanggal = $data['tanggal']; // Jika sudah format tanggal, gunakan apa adanya
                }

                $dataBantuanRw = new bantuan_rw([
                    'jenis_bantuan' => $data['jenis_bantuan'],
                    'tanggal' => $tanggal,
                    'jumlah' => $data['jumlah'],
                    'rw_id' => $id
                ]);
                // Coba simpan data ke database
                if ($dataBantuanRw->save()) {
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

    public function updateBantuanRw(Request $request, $id)
    {
        $validateData = $request->validate([
            'jenis_bantuan' => 'required|string',
            'tanggal' => 'required|date',
            'jumlah' => 'required|integer',
        ]);

        DB::beginTransaction();
        try {
            $dataBantuanRw = bantuan_rw::find($id);
            $dataBantuanRw->jenis_bantuan = $validateData['jenis_bantuan'];
            $dataBantuanRw->tanggal = $validateData['tanggal'];
            $dataBantuanRw->jumlah = $validateData['jumlah'];
            $dataBantuanRw->save();

            DB::commit();
            return response()->json(['message' => 'update data bantuan rw success'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function deleteBantuanRw($id)
    {
        try {
            $dataBantuanRw = bantuan_rw::find($id);

            if ($dataBantuanRw) {
                $dataBantuanRw->delete();
                return response()->json(['message' => 'delete data bantuan rw success'], 200);
            }
            return response()->json(['message' => 'data bantuan rw tidak ditemukan'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }
}
