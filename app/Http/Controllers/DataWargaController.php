<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterDataWarga;
use App\Imports\ImportDataWarga;
use App\Models\MasterKabupaten;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Exports\WargaSheetExport;

class DataWargaController extends Controller
{
    public function importDataWarga(Request $request)
    {
        // Validate the incoming request, including the Excel file and MasterDataWarga data
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls',
            // 'kategori_warga' => 'required',
            'id_kabupaten' => 'required',
            'id_kecamatan' => 'required',
            // 'id_kelurahan' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }

        try {
            // Import Pemilih data from Excel file
            $importedDataWarga = Excel::toArray(new ImportDataWarga, $request->file('file'))[0];

            // Initialize arrays for success and failure data
            $successData = [];
            $failedData = [];
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
                            // 'jenis_kelamin' => $data['jenis_kelamin'],
                            'alamat' => $data['alamat'],
                            'kategori_warga' => $request->kategori_warga,
                            'id_kabupaten' => $request->id_kabupaten,
                            'id_kecamatan' => $request->id_kecamatan,
                            'id_kelurahan' => $request->id_kelurahan,
                            'pj_id' => auth()->user()->id,
                        ]);
                        $wargaData->save();
                        $successData[] = $wargaData->toArray(); // Simpan data yang sukses
                    } else {
                        $errors[] = "NIK already exists for row " . ($index + 1);
                        $failedData[] = $existingWarga; // Simpan data yang gagal
                    }
                } catch (\Exception $e) {
                    // Handle any exception during the data import process
                    $errors[] = "Error on row " . ($index + 1) . ": " . $e->getMessage();
                    // $failedData[] = $data; // Simpan data yang gagal
                }
            }

            // Jika ada data yang gagal atau sukses, buat file Excel dengan dua sheet
            if (count($successData) > 0 || count($failedData) > 0) {
                return Excel::download(new WargaSheetExport($successData, $failedData), 'laporan_data_warga_export.xlsx');
            }

            // Return success response with summary of import
            return response()->json([
                'message' => 'Data imported successfully.',
                'success_data_count' => count($successData),
                'fail_data_count' => count($failedData),
                'errors' => $errors,
            ]);
        } catch (\Exception $e) {
            // Return error response if the process fails
            return response()->json(['message' => 'An error occurred while importing data.', 'error' => $e->getMessage()], 500);
        }
    }


    public function importDataPenerimaBansos(Request $request)
    {
        // Validate the incoming request, including the Excel file and MasterDataWarga data
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }

        try {
            // Import Pemilih data from Excel file
            $importedDataWarga = Excel::toArray(new ImportDataWarga, $request->file('file'))[0];

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

                    if ($existingWarga->status_bansos == '0') {
                        // If not existing, create a new pemilih entry
                        $existingWarga->status_bansos = '1';
                        $existingWarga->save();
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
                'message' => 'Bansos imported successfully.',
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

    public function exportBansosPdf()
    {
        // Get the data for each kabupaten/kota
        $kabupatenData = MasterKabupaten::with(['wargas' => function ($query) {
            $query->select('id_kabupaten')  // Only select id_kabupaten for grouping
                ->selectRaw('COUNT(*) as total_warga')
                ->selectRaw('SUM(CASE WHEN status_bansos = "1" THEN 1 ELSE 0 END) as sudah_terima')
                ->selectRaw('SUM(CASE WHEN status_bansos = "0" THEN 1 ELSE 0 END) as belum_terima')
                ->groupBy('id_kabupaten');  // Group by id_kabupaten only
        }])->get();

        // Initialize total counters
        $totalWarga = 0;
        $totalSudahTerima = 0;
        $totalBelumTerima = 0;

        // Calculate totals
        foreach ($kabupatenData as $kabupaten) {
            $totalWarga += $kabupaten->wargas->sum('total_warga');
            $totalSudahTerima += $kabupaten->wargas->sum('sudah_terima');
            $totalBelumTerima += $kabupaten->wargas->sum('belum_terima');
        }

        // Prepare the data for the PDF
        $pdf = Pdf::loadView('pdf.bansos_report', compact('kabupatenData', 'totalWarga', 'totalSudahTerima', 'totalBelumTerima'));

        // Return the PDF download
        return $pdf->download('bansos_report.pdf');
    }

    public function listBansos()
    {
        $dataPenerimaBansos = MasterDataWarga::get();

        return response()->json([
            'id' => '1',
            'data' => $dataPenerimaBansos
        ]);
    }

    // GET: Fetch all data warga
    public function listDataWarga(Request $request)
    {
        // Define the number of records per page
        $perPage = $request->get('perPage', 10); // default to 10 if not specified

        // Fetch paginated data
        $dataWarga = MasterDataWarga::join('master_kabupatens', 'master_data_wargas.id_kabupaten', '=', 'master_kabupatens.id')
            ->join('master_kecamatans', 'master_data_wargas.id_kecamatan', '=', 'master_kecamatans.id')
            ->join('master_kelurahans', 'master_data_wargas.id_kelurahan', '=', 'master_kelurahans.id')
            ->select('master_data_wargas.*', 'master_kabupatens.name AS nama_kabupaten', 'master_kecamatans.name AS nama_kecamatan', 'master_kelurahans.name AS nama_kelurahan')
            ->paginate($perPage); // Use pagination

        return response()->json([
            'status' => 'success',
            'data' => $dataWarga
        ]);
    }

    public function listDataWargaByPj($id)
    {
        $dataWarga = MasterDataWarga::join('master_kabupatens', 'master_data_wargas.id_kabupaten', '=', 'master_kabupatens.id')
            ->join('master_kecamatans', 'master_data_wargas.id_kecamatan', '=', 'master_kecamatans.id')
            ->join('master_kelurahans', 'master_data_wargas.id_kelurahan', '=', 'master_kelurahans.id')
            ->select('master_data_wargas.*', 'master_kabupatens.name AS nama_kabupaten', 'master_kecamatans.name AS nama_kecamatan', 'master_kelurahans.name AS nama_kelurahan')
            ->where('pj_id', $id)
            ->get();
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
            'kategori_warga' => 'required|string|max:255',
            'id_kelurahan' => 'required|integer',
        ]);

        // Membuat data baru
        $dataWarga = MasterDataWarga::create([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'kategori_warga' => $request->kategori_warga,
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
            'kategori_warga' => 'sometimes|string|max:255',
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
