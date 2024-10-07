<?php

namespace App\Imports;

use App\Models\pemuka_agama;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportPemukaAgama implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return pemuka_agama|null
     */
    public function model(array $row)
    {
        return new pemuka_agama([
            'nama' =>  $row['nama'],
            'pesantren' =>  $row['pesantren'],
            'alamat' =>  $row['alamat'],
            'kota' =>  $row['kota'],
            'kec' =>  $row['kec'],
            'kel' =>  $row['kel'],
            'support' =>  $row['support']
        ]);
    }
}
