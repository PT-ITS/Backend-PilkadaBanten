<?php

namespace App\Imports;

use App\Models\bantuan_tokoh;
use App\Models\jenis_barang;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ImportBantuanTokoh implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return bantuan_tokoh|null
     */
    public function model(array $row)
    {
        $bantuanTokoh = new bantuan_tokoh([
            'pelaksana'     => $row['pelaksana'],
            'tanggal'    => is_numeric($row['tanggal']) ? Date::excelToDateTimeObject($row['tanggal'])->format('Y-m-d') : $row['tanggal'],
            'lokasi'    => $row['lokasi'],
            'sasaran'    => $row['sasaran'],
            'penanggung_jawab'    => $row['penanggung_jawab'],
        ]);

        $bantuanTokoh->save();

        // Save related jenis_barang entries
        for ($i = 1; isset($row['jenis_barang_' . $i]); $i++) {
            $jenisBarang = new jenis_barang();
            $jenisBarang->jenis_barang = $row['jenis_barang_' . $i];
            $jenisBarang->jumlah = $row['jumlah_' . $i];
            $jenisBarang->bantuan_tokoh_id = $bantuanTokoh->id;
            $jenisBarang->save();
        }

        return $bantuanTokoh;
    }
}
