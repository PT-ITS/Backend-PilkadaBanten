<?php

namespace App\Imports;

use App\Models\bantuan_rt;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportBantuanRt implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return bantuan_rt|null
     */
    public function model(array $row)
    {
        return new bantuan_rt([
            'jenis_bantuan' =>  $row['jenis_bantuan'],
            'tanggal' =>  $row['tanggal'],
            'jumlah' =>  $row['jumlah'],
            'rt_id' =>  $row['rt_id']
        ]);
    }
}
