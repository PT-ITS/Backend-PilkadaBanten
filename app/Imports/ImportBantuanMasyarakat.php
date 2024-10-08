<?php

namespace App\Imports;

use App\Models\bantuan_masyarakat;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportBantuanMasyarakat implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return bantuan_masyarakat|null
     */
    public function model(array $row)
    {
        return new bantuan_masyarakat([
            'pelaksana'     => $row['pelaksana'],
            'tanggal'    => $row['tanggal'],
            'lokasi'    => $row['lokasi'],
            'jenis_barang'    => $row['jenis_barang'],
            'jumlah_yang_disalurkan'    => $row['jumlah_yang_disalurkan'],
            'sasaran_penerima'    => $row['sasaran_penerima'],
            'penanggung_jawab'    => $row['penanggung_jawab'],
        ]);
    }
}
