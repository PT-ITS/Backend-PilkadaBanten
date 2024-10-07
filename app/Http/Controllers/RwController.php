<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ImportRw;
use App\Models\data_rw;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class RwController extends Controller
{
    public function listRw($id)
    {
        $dataRw = data_rw::where('relawan_id', $id)->get();

        return response()->json(['message' => 'get data rw success', 'data' => $dataRw], 200);
    }

    public function importRw(Request $request)
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
            // Import bantuan pemilih from Excel file
            $importedDataRw = Excel::toArray(new ImportRw, $request->file('file'))[0];

            // Initialize counters for success and failure tracking
            $successDataCount = 0;
            $failDataCount = 0;
            // $failedRows = [];
            // $errors = [];

            // Loop through the imported data
            foreach ($importedDataRw as $index => $data) {
                $dataRw = new data_rw([
                    'kota' => $data['kota'],
                    'kec' => $data['kec'],
                    'kel' => $data['kel'],
                    'rw' => $data['rw'],
                    'support' => $data['support'],
                    'relawan_id' => $request->relawan_id,
                ]);
                // Coba simpan data ke database
                if ($dataRw->save()) {
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

    public function updateRw(Request $request, $id)
    {
        $validateData = $request->validate([
            'kota' => 'required',
            'kec' => 'required',
            'kel' => 'required',
            'rw' => 'required',
            'support' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $dataRw = data_rw::find($id);
            $dataRw->kota = $validateData['kota'];
            $dataRw->kec = $validateData['kec'];
            $dataRw->kel = $validateData['kel'];
            $dataRw->rw = $validateData['rw'];
            $dataRw->support = $validateData['support'];
            $dataRw->save();

            DB::commit();
            return response()->json(['message' => 'update data rw success'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function deleteRw($id)
    {
        try {
            $dataRw = data_rw::find($id);

            if ($dataRw) {
                $dataRw->delete();
                return response()->json(['message' => 'delete data rw success'], 200);
            }
            return response()->json(['message' => 'data rw tidak ditemukan'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }
}
