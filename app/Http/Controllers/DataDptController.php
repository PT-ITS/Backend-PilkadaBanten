<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterDataDpt;
use App\Imports\ImportDataDpt;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class DataDptController extends Controller
{
    public function importDataDpt(Request $request)
    {
        // Validate the incoming request, including the Excel file and MasterDataDpt data
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls',
            // 'nik' => 'required',
            // 'nama' => 'required',
            // 'jenis_kelamin' => 'required',
            // 'alamat' => 'required',
            'id_kabupaten' => 'required',
            'id_kecamatan' => 'required',
            'id_kelurahan' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }

        try {
            // Create new MasterDataDpt data
            // $newDataDpt = new MasterDataDpt();
            // $newDataDpt->nik = $request->nik;
            // $newDataDpt->nama = $request->nama;
            // $newDataDpt->jenis_kelamin = $request->jenis_kelamin;
            // $newDataDpt->alamat = $request->alamat;
            // $newDataDpt->id_kabupaten = $request->id_kabupaten;
            // $newDataDpt->id_kecamatan = $request->id_kecamatan;
            // $newDataDpt->id_kelurahan = $request->id_kelurahan;

            // $newDataDpt->save();

            // Import Pemilih data from Excel file
            $importedDataDpt = Excel::toArray(new importDataDpt, $request->file('file'))[0];

            // Initialize counters for success and failure tracking
            $successDataCount = 0;
            $failDataCount = 0;
            $failedRows = [];
            $errors = [];

            // Loop through the imported data
            foreach ($importedDataDpt as $index => $data) {
                try {
                    // Check if NIK already exists in the MasterDataDpt table
                    $existingWarga = MasterDataDpt::where('nik', $data['nik'])->first();

                    if (!$existingWarga) {
                        // If not existing, create a new pemilih entry
                        $wargaDpt = new MasterDataDpt([
                            'nik' => $data['nik'],
                            'nama' => $data['nama'],
                            'jenis_kelamin' => $data['jenis_kelamin'],
                            'alamat' => $data['alamat'],
                            'id_kabupaten' => $data['id_kabupaten'],
                            'id_kecamatan' => $data['id_kecamatan'],
                            'id_kelurahan' => $data['id_kelurahan'],

                        ]);
                        $wargaDpt->save();
                        $successDataCount++; // Increment success count
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

    // GET: Fetch all data DPT
    public function index()
    {
        $dataDpt = MasterDataDpt::all();
        return response()->json([
            'status' => 'success',
            'data' => $dataDpt
        ]);
    }

    // POST: Store new data DPT
    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'nik' => 'required|string|max:16',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string|max:1', // L for Male, P for Female
            'alamat' => 'required|string|max:255',
            'id_kelurahan' => 'required|integer',
        ]);

        // Membuat data baru
        $dataDpt = MasterDataDpt::create([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'id_kelurahan' => $request->id_kelurahan,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data DPT berhasil ditambahkan',
            'data' => $dataDpt
        ]);
    }

    // GET: Fetch single data DPT by ID
    public function show($id)
    {
        $dataDpt = MasterDataDpt::find($id);

        if (!$dataDpt) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data DPT tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $dataDpt
        ]);
    }

    // PUT: Update data DPT
    public function update(Request $request, $id)
    {
        $dataDpt = MasterDataDpt::find($id);

        if (!$dataDpt) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data DPT tidak ditemukan',
            ], 404);
        }

        // Validasi data
        $request->validate([
            'nik' => 'sometimes|string|max:16',
            'nama' => 'sometimes|string|max:255',
            'jenis_kelamin' => 'sometimes|string|max:1',
            'alamat' => 'sometimes|string|max:255',
            'id_kelurahan' => 'sometimes|integer',
        ]);

        // Update data
        $dataDpt->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Data DPT berhasil diperbarui',
            'data' => $dataDpt
        ]);
    }

    // DELETE: Hapus data DPT
    public function destroy($id)
    {
        $dataDpt = MasterDataDpt::find($id);

        if (!$dataDpt) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data DPT tidak ditemukan',
            ], 404);
        }

        $dataDpt->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data DPT berhasil dihapus'
        ]);
    }
}
