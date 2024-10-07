<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\BantuanTokohService;
use App\Imports\ImportBantuanTokoh;
use App\Models\bantuan_tokoh;
use App\Models\jenis_barang;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class BantuanTokohController extends Controller
{
    private $bantuanTokohService;

    public function __construct(BantuanTokohService $bantuanTokohService)
    {
        $this->bantuanTokohService = $bantuanTokohService;
    }

    public function listDataBantuanTokoh()
    {
        $result = $this->bantuanTokohService->listDataBantuanTokoh();
        return response()->json(
            [
                'message' => $result['message'],
                'data' => $result['data']
            ],
            $result['statusCode']
        );
    }

    public function detailDataBantuanTokoh($id)
    {
        $result = $this->bantuanTokohService->detailDataBantuanTokoh($id);
        return response()->json(
            [
                'message' => $result['message'],
                'data' => $result['data']
            ],
            $result['statusCode']
        );
    }

    public function importDataBantuanTokoh(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }

        try {
            // Simpan data yang akan diimpor
            $importedData = Excel::toArray(new ImportBantuanTokoh, $request->file)[0];

            // Inisialisasi variabel hitungan
            $successDataCount = 0;
            $failDataCount = 0;

            foreach ($importedData as $data) {
                if (is_numeric($data['tanggal'])) {
                    $tanggal = Date::excelToDateTimeObject($data['tanggal'])->format('Y-m-d');
                } else {
                    $tanggal = $data['tanggal']; // Jika sudah format tanggal, gunakan apa adanya
                }

                // Buat instance bantuan_tokoh
                $dataBantuanTokoh = new bantuan_tokoh([
                    'pelaksana'  => $data['pelaksana'],
                    'tanggal' => $tanggal,
                    'lokasi' => $data['lokasi'],
                    'sasaran' => $data['sasaran'],
                    'penanggung_jawab' => $data['penanggung_jawab'],
                ]);

                if ($dataBantuanTokoh->save()) {
                    // Jika berhasil, tambahkan ke hitungan data yang berhasil
                    $successDataCount++;

                    // Simpan jenis_barang terkait
                    for ($i = 1; isset($data['jenis_barang_' . $i]); $i++) {
                        $jenisBarang = new jenis_barang();
                        $jenisBarang->jenis_barang = $data['jenis_barang_' . $i];
                        $jenisBarang->jumlah = $data['jumlah_' . $i];
                        $jenisBarang->bantuan_tokoh_id = $dataBantuanTokoh->id;
                        $jenisBarang->save();
                    }
                } else {
                    // Jika gagal disimpan ke database, tambahkan ke hitungan data yang gagal
                    $failDataCount++;
                }
            }

            return response()->json([
                'message' => 'Data bantuan tokoh berhasil diimpor.',
                'success_data_count' => $successDataCount,
                'fail_data_count' => $failDataCount,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function inputDataBantuanTokoh(Request $request)
    {
        try {
            // Validasi data bantuan tokoh
            $validateBantuanTokohData = $request->validate([
                'bantuanTokoh.pelaksana' => 'required|string',
                'bantuanTokoh.tanggal' => 'required|date',
                'bantuanTokoh.lokasi' => 'required|string',
                'bantuanTokoh.sasaran' => 'required|string',
                'bantuanTokoh.penanggung_jawab' => 'required|string',
            ]);

            $validateJenisBarangData = $request->validate([
                'jenisBarang.*.jenis_barang' => 'required|string',
                'jenisBarang.*.jumlah' => 'required|integer',
            ]);

            DB::beginTransaction();

            try {
                // Simpan data bantuanTokoh
                $bantuanTokoh = new bantuan_tokoh();
                $bantuanTokoh->pelaksana = $validateBantuanTokohData['bantuanTokoh']['pelaksana'];
                $bantuanTokoh->tanggal = $validateBantuanTokohData['bantuanTokoh']['tanggal'];
                $bantuanTokoh->lokasi = $validateBantuanTokohData['bantuanTokoh']['lokasi'];
                $bantuanTokoh->sasaran = $validateBantuanTokohData['bantuanTokoh']['sasaran'];
                $bantuanTokoh->penanggung_jawab = $validateBantuanTokohData['bantuanTokoh']['penanggung_jawab'];
                $bantuanTokoh->save();

                // Simpan data barang
                foreach ($validateJenisBarangData['jenisBarang'] as $dataBarang) {
                    $jenisBarang = new jenis_barang();
                    $jenisBarang->jenis_barang = $dataBarang['jenis_barang'];
                    $jenisBarang->jumlah = $dataBarang['jumlah'];
                    $jenisBarang->bantuan_tokoh_id = $bantuanTokoh->id;
                    $jenisBarang->save();
                }

                DB::commit();
                return response()->json(['message' => 'Data bantuan tokoh berhasil disimpan'], 201);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data', 'error' => $e->getMessage()], 500);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        }
    }


    public function updateDataBantuanTokoh(Request $request, $id)
    {
        $validateData = $request->validate([
            'pelaksana' => 'required|string',
            'tanggal' => 'required|date',
            'lokasi' => 'required|string',
            'sasaran' => 'required|string',
            'penanggung_jawab' => 'required|string',
        ]);

        $result = $this->bantuanTokohService->updateDataBantuanTokoh($validateData, $id);
        return response()->json(
            [
                'message' => $result['message']
            ],
            $result['statusCode']
        );
    }

    public function deleteDataBantuanTokoh($id)
    {
        $result = $this->bantuanTokohService->deleteDataBantuanTokoh($id);
        return response()->json(
            [
                'message' => $result['message']
            ],
            $result['statusCode']
        );
    }
}
