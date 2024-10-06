<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\DukunganTokohService;
use App\Imports\ImportDukunganTokoh;
use App\Models\dukungan_tokoh;
use App\Models\jenis_dukungan;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DukunganTokohController extends Controller
{
    private $dukunganTokohService;

    public function __construct(DukunganTokohService $dukunganTokohService)
    {
        $this->dukunganTokohService = $dukunganTokohService;
    }

    public function listDataDukunganTokoh()
    {
        $result = $this->dukunganTokohService->listDataDukunganTokoh();
        return response()->json(
            [
                'message' => $result['message'],
                'data' => $result['data']
            ],
            $result['statusCode']
        );
    }

    public function detailDataDukunganTokoh($id)
    {
        $result = $this->dukunganTokohService->detailDataDukunganTokoh($id);
        return response()->json(
            [
                'message' => $result['message'],
                'data' => $result['data']
            ],
            $result['statusCode']
        );
    }

    public function importDataDukunganTokoh(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            // Simpan data yang akan diimpor
            $importedData = Excel::toArray(new ImportDukunganTokoh, $request->file)[0];

            // Inisialisasi variabel hitungan
            $successDataCount = 0;
            $failDataCount = 0;

            foreach ($importedData as $data) {
                if (is_numeric($data['tanggal'])) {
                    $tanggal = Date::excelToDateTimeObject($data['tanggal'])->format('Y-m-d');
                } else {
                    $tanggal = $data['tanggal']; // Jika sudah format tanggal, gunakan apa adanya
                }

                // Buat instance dukungan_tokoh
                $dataDukunganTokoh = new dukungan_tokoh([
                    'pelaksana'  => $data['pelaksana'],
                    'tanggal' => $tanggal,
                    'lokasi' => $data['lokasi'],
                    'sasaran' => $data['sasaran'],
                    'penanggung_jawab' => $data['penanggung_jawab'],
                ]);

                if ($dataDukunganTokoh->save()) {
                    // Jika berhasil, tambahkan ke hitungan data yang berhasil
                    $successDataCount++;

                    // Simpan jenis_dukungan terkait
                    for ($i = 1; isset($data['jenis_dukungan_' . $i]); $i++) {
                        $jenisDukungan = new jenis_dukungan();
                        $jenisDukungan->jenis_dukungan = $data['jenis_dukungan_' . $i];
                        $jenisDukungan->jumlah = $data['jumlah_' . $i];
                        $jenisDukungan->dukungan_tokoh_id = $dataDukunganTokoh->id;
                        $jenisDukungan->save();
                    }
                } else {
                    // Jika gagal disimpan ke database, tambahkan ke hitungan data yang gagal
                    $failDataCount++;
                }
            }

            return response()->json([
                'message' => 'Data dukungan tokoh berhasil diimpor.',
                'success_data_count' => $successDataCount,
                'fail_data_count' => $failDataCount,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function inputDataDukunganTokoh(Request $request)
    {
        try {
            // Validasi data dukungan tokoh
            $validateDukunganTokohData = $request->validate([
                'dukunganTokoh.pelaksana' => 'required|string',
                'dukunganTokoh.tanggal' => 'required|date',
                'dukunganTokoh.lokasi' => 'required|string',
                'dukunganTokoh.sasaran' => 'required|string',
                'dukunganTokoh.penanggung_jawab' => 'required|string',
            ]);

            $validateJenisBarangData = $request->validate([
                'jenisDukungan.*.jenis_dukungan' => 'required|string',
                'jenisDukungan.*.jumlah' => 'required|integer',
            ]);

            DB::beginTransaction();

            try {
                // Simpan data dukunganTokoh
                $dukunganTokoh = new dukungan_tokoh();
                $dukunganTokoh->pelaksana = $validateDukunganTokohData['dukunganTokoh']['pelaksana'];
                $dukunganTokoh->tanggal = $validateDukunganTokohData['dukunganTokoh']['tanggal'];
                $dukunganTokoh->lokasi = $validateDukunganTokohData['dukunganTokoh']['lokasi'];
                $dukunganTokoh->sasaran = $validateDukunganTokohData['dukunganTokoh']['sasaran'];
                $dukunganTokoh->penanggung_jawab = $validateDukunganTokohData['dukunganTokoh']['penanggung_jawab'];
                $dukunganTokoh->save();

                // Simpan data barang
                foreach ($validateJenisBarangData['jenisDukungan'] as $dataBarang) {
                    $jenisDukungan = new jenis_dukungan();
                    $jenisDukungan->jenis_dukungan = $dataBarang['jenis_dukungan'];
                    $jenisDukungan->jumlah = $dataBarang['jumlah'];
                    $jenisDukungan->dukungan_tokoh_id = $dukunganTokoh->id;
                    $jenisDukungan->save();
                }

                DB::commit();
                return response()->json(['message' => 'Data dukungan tokoh berhasil disimpan'], 201);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data', 'error' => $e->getMessage()], 500);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        }
    }


    public function updateDataDukunganTokoh(Request $request, $id)
    {
        $validateData = $request->validate([
            'pelaksana' => 'required|string',
            'tanggal' => 'required|date',
            'lokasi' => 'required|string',
            'sasaran' => 'required|string',
            'penanggung_jawab' => 'required|string',
        ]);

        $result = $this->dukunganTokohService->updateDataDukunganTokoh($validateData, $id);
        return response()->json(
            [
                'message' => $result['message']
            ],
            $result['statusCode']
        );
    }

    public function deleteDataDukunganTokoh($id)
    {
        $result = $this->dukunganTokohService->deleteDataDukunganTokoh($id);
        return response()->json(
            [
                'message' => $result['message']
            ],
            $result['statusCode']
        );
    }
}
