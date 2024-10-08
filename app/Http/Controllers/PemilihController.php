<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ImportPemilih;
use App\Models\data_pemilih;
use App\Models\Relawan;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class PemilihController extends Controller
{
    public function listRelawan()
    {
        $dataRelawan = Relawan::get();

        return response()->json(['id' => '1', 'data' => $dataRelawan]);
    }

    public function listPemilihByRelawan($id)
    {
        // Ambil data relawan berdasarkan id
        $relawan = Relawan::find($id);
    
        // Cek apakah data relawan ditemukan
        if ($relawan) {
            // Ambil data pemilih yang terkait dengan relawan ini
            $dataPemilih = $relawan->data_pemilih()->get(); // Menggunakan relasi hasMany
            
            // Gabungkan data relawan dan data pemilih dalam array 'data'
            return response()->json([
                'id' => $id,
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
                    'data_pemilih' => $dataPemilih // Daftar data pemilih
                ]
            ]);
        } else {
            return response()->json(['message' => 'Relawan tidak ditemukan'], 404);
        }
    }
    
    public function importDataPemilih(Request $request)
    {
        // Validate the incoming request, including the Excel file and relawan data
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls',
            'nik' => 'required',
            'nama' => 'required',
            'alamat' => 'required',
            'kota' => 'required',
            'kec' => 'required',
            'kel' => 'required',
            'rt_rw' => 'required',
            'jumlah_data' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }

        try {
            // Create new Relawan data
            $dataRelawan = new Relawan();
            $dataRelawan->nik = $request->nik;
            $dataRelawan->nama = $request->nama;
            $dataRelawan->alamat = $request->alamat;
            $dataRelawan->kota = $request->kota;
            $dataRelawan->kec = $request->kec;
            $dataRelawan->kel = $request->kel;
            $dataRelawan->rt_rw = $request->rt_rw;
            $dataRelawan->jumlah_data = $request->jumlah_data;
            $dataRelawan->save();

            // Import Pemilih data from Excel file
            $importedDataPemilih = Excel::toArray(new ImportPemilih, $request->file('file'))[0];

            // Initialize counters for success and failure tracking
            $successDataCount = 0;
            $failDataCount = 0;
            $failedRows = [];
            $errors = [];

            // Loop through the imported data
            foreach ($importedDataPemilih as $index => $data) {
                try {
                    // Check if NIK already exists in the data_pemilih table
                    $existingPemilih = data_pemilih::where('nik', $data['nik'])->first();

                    if (!$existingPemilih) {
                        // If not existing, create a new pemilih entry
                        $pemilihData = new data_pemilih([
                            'nik' => $data['nik'],
                            'nama' => $data['nama'],
                            'alamat' => $data['alamat'],
                            'kota' => $data['kota'],
                            'kec' => $data['kec'],
                            'desa_kel' => $data['desa_kel'],
                            'rt_rw' => $data['rt_rw'],
                            'tps' => $data['tps'],
                            'relawan_id' => $dataRelawan->id
                        ]);
                        $pemilihData->save();
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

    public function importPemilihByRelawan(Request $request)
    {
        // Validate the incoming request, including the Excel file and relawan data
        $validator = Validator::make($request->all(), [
            'id_relawan' => 'required',
            'file' => 'required|mimes:xlsx,xls',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }

        try {
            // Import Pemilih data from Excel file
            $importedDataPemilih = Excel::toArray(new ImportPemilih, $request->file('file'))[0];

            // Initialize counters for success and failure tracking
            $successDataCount = 0;
            $failDataCount = 0;
            $failedRows = [];
            $errors = [];

            // Loop through the imported data
            foreach ($importedDataPemilih as $index => $data) {
                try {
                    // Check if NIK already exists in the data_pemilih table
                    $existingPemilih = data_pemilih::where('nik', $data['nik'])->first();

                    if (!$existingPemilih) {
                        // If not existing, create a new pemilih entry
                        $pemilihData = new data_pemilih([
                            'nik' => $data['nik'],
                            'nama' => $data['nama'],
                            'alamat' => $data['alamat'],
                            'kota' => $data['kota'],
                            'kec' => $data['kec'],
                            'desa_kel' => $data['desa_kel'],
                            'rt_rw' => $data['rt_rw'],
                            'tps' => $data['tps'],
                            'relawan_id' => $request->id_relawan
                        ]);
                        $pemilihData->save();
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


    public function updateRelawan(Request $request, $id)
    {
        $validateData = $request->validate([
            'nik' => 'required',
            'nama' => 'required',
            'alamat' => 'required',
            'kota' => 'required',
            'kec' => 'required',
            'kel' => 'required',
            'rt_rw' => 'required',
            'jumlah_data' => 'required|integer',
        ]);

        DB::beginTransaction();
        try {
            $dataRelawan = Relawan::find($id);
            $dataRelawan->nik = $validateData['nik'];
            $dataRelawan->nama = $validateData['nama'];
            $dataRelawan->alamat = $validateData['alamat'];
            $dataRelawan->kota = $validateData['kota'];
            $dataRelawan->kec = $validateData['kec'];
            $dataRelawan->kel = $validateData['kel'];
            $dataRelawan->rt_rw = $validateData['rt_rw'];
            $dataRelawan->jumlah_data = $validateData['jumlah_data'];
            $dataRelawan->save();

            DB::commit();
            return response()->json(['message' => 'update data bantuan pemlih success'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function deleteRelawan($id)
    {
        $dataRelawan = Relawan::find($id);

        if ($dataRelawan) {
            $dataRelawan->delete();
            return response()->json(['id' => '1', 'data' => 'data relawan berhasil di hapus']);
        }
        return response()->json(['id' => '0', 'data' => 'data relawan gagal di hapus']);
    }
}
