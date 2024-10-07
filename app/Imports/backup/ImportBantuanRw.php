<?php

namespace App\Imports;

use App\Models\bantuan_rw;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportBantuanRw implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return bantuan_rw|null
     */
    public function model(array $row)
    {
        return new bantuan_rw([
            'jenis_bantuan' =>  $row['jenis_bantuan'],
            'tanggal' =>  $row['tanggal'],
            'jumlah' =>  $row['jumlah'],
            'rw_id' =>  $row['rw_id']
        ]);
    }
}
