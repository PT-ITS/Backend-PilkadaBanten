<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\BantuanMasyarakatService;
use App\Imports\ImportBantuanMasyarakat;
use App\Models\bantuan_masyarakat;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class BantuanMasyarakatController extends Controller
{
    private $bantuanMasyarakatService;

    public function __construct(BantuanMasyarakatService $bantuanMasyarakatService)
    {
        $this->bantuanMasyarakatService = $bantuanMasyarakatService;
    }

    public function listDataBantuanMasyarakat()
    {
        $result = $this->bantuanMasyarakatService->listDataBantuanMasyarakat();
        return response()->json(
            [
                'message' => $result['message'],
                'data' => $result['data']
            ],
            $result['statusCode']
        );
    }

    public function detailDataBantuanMasyarakat($id)
    {
        $result = $this->bantuanMasyarakatService->detailDataBantuanMasyarakat($id);
        return response()->json(
            [
                'message' => $result['message'],
                'data' => $result['data']
            ],
            $result['statusCode']
        );
    }

    public function importDataBantuanMasyarakat(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            // Simpan data yang akan diimpor
            $importedData = Excel::toArray(new ImportBantuanMasyarakat, $request->file)[0];

            // Inisialisasi variabel hitungan
            $successDataCount = 0;
            $failDataCount = 0;

            foreach ($importedData as $data) {
                if (is_numeric($data['tanggal'])) {
                    $tanggal = Date::excelToDateTimeObject($data['tanggal'])->format('Y-m-d');
                } else {
                    $tanggal = $data['tanggal']; // Jika sudah format tanggal, gunakan apa adanya
                }

                // Lakukan validasi atau manipulasi data sesuai kebutuhan
                $karyawan = new bantuan_masyarakat([
                    'pelaksana'  => $data['pelaksana'],
                    'tanggal' => $tanggal,
                    'lokasi' => $data['lokasi'],
                    'jenis_barang' => $data['jenis_barang'],
                    'jumlah_yang_disalurkan' => $data['jumlah_yang_disalurkan'],
                    'sasaran_penerima' => $data['sasaran_penerima'],
                    'penanggung_jawab' => $data['penanggung_jawab'],
                ]);

                // Coba simpan user ke database
                if ($karyawan->save()) {
                    // Jika berhasil, tambahkan ke hitungan data yang berhasil
                    $successDataCount++;
                } else {
                    // Jika gagal disimpan ke database, tambahkan ke hitungan data yang gagal
                    $failDataCount++;
                }
            }

            return response()->json([
                'message' => 'Data bantuan masyarakat berhasil diimpor.',
                'success_data_count' => $successDataCount,
                'fail_data_count' => $failDataCount,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function inputDataBantuanMasyarakat(Request $request)
    {
        // Validasi data bantuanMasyarakat
        $validateBantuanMasyarakatData = $request->validate([
            'pelaksana' => 'required',
            'tanggal' => 'required',
            'lokasi' => 'required',
            'jenis_barang' => 'required',
            'jumlah_yang_disalurkan' => 'required',
            'sasaran_penerima' => 'required',
            'penanggung_jawab' => 'required',
        ]);

        DB::beginTransaction();

        try {
            // Simpan data bantuanMasyarakat
            $bantuanMasyarakat = new bantuan_masyarakat();
            $bantuanMasyarakat->fill($validateBantuanMasyarakatData);
            $bantuanMasyarakat->save();

            DB::commit();
            return response()->json(['message' => 'Data bantuan masyarakat berhasil disimpan'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateDataBantuanMasyarakat(Request $request, $id)
    {
        $validateData = $request->validate([
            'pelaksana' => 'required',
            'tanggal' => 'required',
            'lokasi' => 'required',
            'jenis_barang' => 'required',
            'jumlah_yang_disalurkan' => 'required',
            'sasaran_penerima' => 'required',
            'penanggung_jawab' => 'required',
        ]);

        $result = $this->bantuanMasyarakatService->updateDataBantuanMasyarakat($validateData, $id);
        return response()->json(
            [
                'message' => $result['message']
            ],
            $result['statusCode']
        );
    }

    public function deleteDataBantuanMasyarakat($id)
    {
        $result = $this->bantuanMasyarakatService->deleteDataBantuanMasyarakat($id);
        return response()->json(
            [
                'message' => $result['message']
            ],
            $result['statusCode']
        );
    }
}
