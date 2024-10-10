<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterDataWarga;
use App\Imports\ImportDataWarga;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class DataWargaController extends Controller
{
    public function importDataWarga(Request $request)
    {
        // Validate the incoming request, including the Excel file and MasterDataWarga data
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls',
            'nik' => 'required',
            'nama' => 'required',
            'jenis_kelamin' => 'required',
            'alamat' => 'required',
            'id_kelurahan' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }

        try {
            // Create new MasterDataWarga data
            $dataWarga = new MasterDataWarga();
            $dataWarga->nik = $request->nik;
            $dataWarga->nama = $request->nama;
            $dataWarga->jenis_kelamin = $request->jenis_kelamin;
            $dataWarga->alamat = $request->alamat;
            $dataWarga->id_kelurahan = $request->id_kelurahan;
            $dataWarga->save();

            // Import Pemilih data from Excel file
            $importedDataWarga = Excel::toArray(new ImportPemilih, $request->file('file'))[0];

            // Initialize counters for success and failure tracking
            $successDataCount = 0;
            $failDataCount = 0;
            $failedRows = [];
            $errors = [];

            // Loop through the imported data
            foreach ($importedDataWarga as $index => $data) {
                try {
                    // Check if NIK already exists in the MasterDataWarga table
                    $existingWarga = MasterDataWarga::where('nik', $data['nik'])->first();

                    if (!$existingWarga) {
                        // If not existing, create a new pemilih entry
                        $wargaData = new MasterDataWarga([
                            'nik' => $data['nik'],
                            'nama' => $data['nama'],
                            'jenis_kelamin' => $data['jenis_kelamin'],
                            'alamat' => $data['alamat'],
                            'id_kelurahan' => $data['id_kelurahan'],
                        ]);
                        $wargaData->save();
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

    // GET: Fetch all data warga
    public function index()
    {
        $dataWarga = MasterDataWarga::all();
        return response()->json([
            'status' => 'success',
            'data' => $dataWarga
        ]);
    }

    // POST: Store new data warga
    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'nik' => 'required|string|max:16',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string|max:1', // Example: L for Male, P for Female
            'alamat' => 'required|string|max:255',
            'id_kelurahan' => 'required|integer',
        ]);

        // Membuat data baru
        $dataWarga = MasterDataWarga::create([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'id_kelurahan' => $request->id_kelurahan,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data warga berhasil ditambahkan',
            'data' => $dataWarga
        ]);
    }

    // GET: Fetch single data warga by ID
    public function show($id)
    {
        $dataWarga = MasterDataWarga::find($id);

        if (!$dataWarga) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data warga tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $dataWarga
        ]);
    }

    // PUT: Update data warga
    public function update(Request $request, $id)
    {
        $dataWarga = MasterDataWarga::find($id);

        if (!$dataWarga) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data warga tidak ditemukan',
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
        $dataWarga->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Data warga berhasil diperbarui',
            'data' => $dataWarga
        ]);
    }

    // DELETE: Hapus data warga
    public function destroy($id)
    {
        $dataWarga = MasterDataWarga::find($id);

        if (!$dataWarga) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data warga tidak ditemukan',
            ], 404);
        }

        $dataWarga->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data warga berhasil dihapus'
        ]);
    }
}
