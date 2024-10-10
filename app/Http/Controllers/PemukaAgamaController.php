<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ImportPemukaAgama;
use App\Models\pemuka_agama;
use App\Models\relawan;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class PemukaAgamaController extends Controller
{
    public function listPemukaAgama($id)
    {
        $relawan = relawan::find($id);
        $dataPemukaAgama = pemuka_agama::where('relawan_id', $id)->get();

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
                'data_pemuka_agama' => $dataPemukaAgama
            ]
        ], 200);
    }

    public function importPemukaAgama(Request $request)
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
            $importedDataPemukaAgama = Excel::toArray(new ImportPemukaAgama, $request->file('file'))[0];

            // Initialize counters for success and failure tracking
            $successDataCount = 0;
            $failDataCount = 0;
            $failedRows = [];
            $errors = [];

            // Loop through the imported data
            foreach ($importedDataPemukaAgama as $index => $data) {
                try {
                    // Check if NIK already exists in the data_pemilih table
                    $existingPemukaAgama = pemuka_agama::where('nik', $data['nik'])->first();

                    if (!$existingPemukaAgama) {
                        $dataPemukaAgama = new pemuka_agama([
                            'nik' => $data['nik'],
                            'nama' => $data['nama'],
                            'pesantren' => $data['pesantren'],
                            'alamat' => $data['alamat'],
                            'kota' => $data['kota'],
                            'kec' => $data['kec'],
                            'kel' => $data['kel'],
                            'support' => $data['support'],
                            'relawan_id' => $request->relawan_id,
                        ]);
                        $dataPemukaAgama->save();
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

    public function updatePemukaAgama(Request $request, $id)
    {
        $validateData = $request->validate([
            'nama' => 'required',
            'pesantren' => 'required',
            'alamat' => 'required',
            'kota' => 'required',
            'kec' => 'required',
            'kel' => 'required',
            'support' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $dataPemukaAgama = pemuka_agama::find($id);
            $dataPemukaAgama->nama = $validateData['nama'];
            $dataPemukaAgama->pesantren = $validateData['pesantren'];
            $dataPemukaAgama->alamat = $validateData['alamat'];
            $dataPemukaAgama->kota = $validateData['kota'];
            $dataPemukaAgama->kec = $validateData['kec'];
            $dataPemukaAgama->kel = $validateData['kel'];
            $dataPemukaAgama->support = $validateData['support'];
            $dataPemukaAgama->save();

            DB::commit();
            return response()->json(['message' => 'update data pemuka agama success'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function deletePemukaAgama($id)
    {
        try {
            $dataPemukaAgama = pemuka_agama::find($id);

            if ($dataPemukaAgama) {
                $dataPemukaAgama->delete();
                return response()->json(['message' => 'delete data pemuka agama success'], 200);
            }
            return response()->json(['message' => 'data pemuka agama tidak ditemukan'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }
}
