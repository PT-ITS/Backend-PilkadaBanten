<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ImportRt;
use App\Models\relawan;
use App\Models\data_rt;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class RtController extends Controller
{
    public function listRt($id)
    {
        $relawan = relawan::find($id);
        $dataRt = data_rt::where('relawan_id', $id)->get();

        return response()->json([
            'message' => 'get data rt success',
            'data' => [
                'relawan' => [
                    'nik' => $relawan->nik,
                    'nama' => $relawan->nama,
                    'alamat' => $relawan->alamat,
                    'kota' => $relawan->kota,
                    'kec' => $relawan->kec,
                    'kel' => $relawan->kel,
                    'rt_rw' => $relawan->rt_rw
                ],
                'data_rt' => $dataRt
            ]
        ], 200);
    }

    public function importRt(Request $request)
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
            $importedDataRt = Excel::toArray(new ImportRt, $request->file('file'))[0];

            // Initialize counters for success and failure tracking
            $successDataCount = 0;
            $failDataCount = 0;
            $failedRows = [];
            $errors = [];

            // Loop through the imported data
            foreach ($importedDataRt as $index => $data) {
                try {
                    // Check if NIK already exists in the data_pemilih table
                    $existingRt = data_rt::where('nik', $data['nik'])->first();

                    if (!$existingRt) {
                        $dataRt = new data_rt([
                            'nik' => $data['nik'],
                            'nama' => $data['nama'],
                            'kota' => $data['kota'],
                            'kec' => $data['kec'],
                            'kel' => $data['kel'],
                            'rw' => $data['rw'],
                            'rt' => $data['rt'],
                            'support' => $data['support'],
                            'relawan_id' => $request->relawan_id,
                        ]);
                        $dataRt->save();
                        $successDataCount++;
                    } else {
                        $errors[] = "NIK already exists for row " . ($index + 1);
                        $failDataCount++; // Increment fail count
                        $failedRows[] = $index + 1;
                    }
                } catch (\Exception $e) {
                    // Handle any exception during the data import process
                    $failDataCount++; // Increment fail count
                    $failedRows[] = $index + 1;
                    $errors[] = "Error on row " . ($index + 1) . ": " . $e->getMessage();
                }
            }

            // Return success response with summary of import
            return response()->json([
                'message' => 'Data imported successfully.',
                'success_data_count' => $successDataCount,
                'fail_data_count' => $failDataCount,
                'failed_rows' => $failedRows,
                'errors' => $errors,
            ]);
        } catch (\Exception $e) {
            // Return error response if the process fails
            return response()->json(['message' => 'An error occurred while importing data.', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateRt(Request $request, $id)
    {
        $validateData = $request->validate([
            'kota' => 'required',
            'kec' => 'required',
            'kel' => 'required',
            'rw' => 'required',
            'rt' => 'required',
            'support' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $dataRt = data_rt::find($id);
            $dataRt->kota = $validateData['kota'];
            $dataRt->kec = $validateData['kec'];
            $dataRt->kel = $validateData['kel'];
            $dataRt->rw = $validateData['rw'];
            $dataRt->rt = $validateData['rt'];
            $dataRt->support = $validateData['support'];
            $dataRt->save();

            DB::commit();
            return response()->json(['message' => 'update data rt success'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function deleteRt($id)
    {
        try {
            $dataRt = data_rt::find($id);

            if ($dataRt) {
                $dataRt->delete();
                return response()->json(['message' => 'delete data rt success'], 200);
            }
            return response()->json(['message' => 'data rt tidak ditemukan'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }
}
