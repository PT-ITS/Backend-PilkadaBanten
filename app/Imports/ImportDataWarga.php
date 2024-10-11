<?php

namespace App\Imports;

use App\Models\MasterDataWarga;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportDataWarga implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return MasterDataWarga|null
     */
    public function model(array $row)
    {
        return new MasterDataWarga([
            'nik' => $row['nik'],
            'nama' => $row['nama'],
            'jenis_kelamin' => $row['jenis_kelamin'],
            'alamat' => $row['alamat'],
            'id_kelurahan' => $row['id_kelurahan'],
        ]);
    }
}
