<?php

namespace App\Imports;

use App\Models\data_rw;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportRw implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return data_rw|null
     */
    public function model(array $row)
    {
        return new data_rw([
            'nik' =>  $row['nik'],
            'nama' =>  $row['nama'],
            'kota' =>  $row['kota'],
            'kec' =>  $row['kec'],
            'kel' =>  $row['kel'],
            'rw' =>  $row['rw'],
            'support' =>  $row['support']
        ]);
    }
}
