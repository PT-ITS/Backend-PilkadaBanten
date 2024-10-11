<?php

namespace App\Imports;

use App\Models\MasterDataDpt;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportDataDpt implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return MasterDataDpt|null
     */
    public function model(array $row)
    {
        return new MasterDataDpt([
            'nik' => $row['nik'],
            'nama' => $row['nama'],
            'jenis_kelamin' => $row['jenis_kelamin'],
            'alamat' => $row['alamat'],
        ]);
    }
}
