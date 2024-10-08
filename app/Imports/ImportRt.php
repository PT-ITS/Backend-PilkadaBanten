<?php

namespace App\Imports;

use App\Models\data_rt;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportRt implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return data_rt|null
     */
    public function model(array $row)
    {
        return new data_rt([
            'nik' =>  $row['nik'],
            'nama' =>  $row['nama'],
            'kota' =>  $row['kota'],
            'kec' =>  $row['kec'],
            'kel' =>  $row['kel'],
            'rw' =>  $row['rw'],
            'rt' =>  $row['rt'],
            'support' =>  $row['support']
        ]);
    }
}
