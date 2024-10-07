<?php

namespace App\Imports;

use App\Models\data_pemilih;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportPemilih implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return data_pemilih|null
     */
    public function model(array $row)
    {
        return new data_pemilih([
            'nik' =>  $row['nik'],
            'nama' =>  $row['nama'],
            'alamat' =>  $row['alamat'],
            'kota' =>  $row['kota'],
            'kec' =>  $row['kec'],
            'desa_kel' =>  $row['desa_kel'],
            'rt_rw' =>  $row['rt_rw'],
            'tps' =>  $row['tps'],
            'relawan_id' =>  $row['relawan_id']
        ]);
    }
}
