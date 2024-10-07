<?php

namespace App\Imports;

use App\Models\bantuan_pemilih;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportBantuanPemilih implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return bantuan_pemilih|null
     */
    public function model(array $row)
    {
        return new bantuan_pemilih([
            'jenis_bantuan' =>  $row['jenis_bantuan'],
            'tanggal' =>  $row['tanggal'],
            'jumlah' =>  $row['jumlah'],
            'relawan_id' =>  $row['relawan_id']
        ]);
    }
}
