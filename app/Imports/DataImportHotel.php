<?php

namespace App\Imports;

use App\Models\Hotel;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataImportHotel implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return Hotel|null
     */
    public function model(array $row)
    {
        return new Hotel([
            'nib'           => $row['nib'],
            'namaHotel'     => $row['namaHotel'],
            'bintangHotel'  => $row['bintangHotel'],
            'kamarVip'      => $row['kamarVip'],
            'kamarStandart' => $row['kamarStandart'],
            'resiko'        => $row['resiko'],
            'skalaUsaha'    => $row['skalaUsaha'],
            'alamat'        => $row['alamat'],
            'koordinat'     => $row['koordinat'],
            'namaPj'        => $row['namaPj'],
            'nikPj'         => $row['nikPj'],
            'pendidikanPj'  => $row['pendidikanPj'],
            'teleponPj'     => $row['teleponPj'],
            'wargaNegaraPj' => $row['wargaNegaraPj'],
            'surveyor_id'   => $row['surveyor_id'],
        ]);
    }
}