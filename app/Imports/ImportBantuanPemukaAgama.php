<?php

namespace App\Imports;

use App\Models\bantuan_pemuka_agama;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportBantuanPemukaAgama implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return bantuan_pemuka_agama|null
     */
    public function model(array $row)
    {
        return new bantuan_pemuka_agama([
            'jenis_bantuan' =>  $row['jenis_bantuan'],
            'tanggal' =>  $row['tanggal'],
            'jumlah' =>  $row['jumlah'],
            'pemuka_agama_id' =>  $row['pemuka_agama_id']
        ]);
    }
}
