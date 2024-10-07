<?php

namespace App\Imports;

use App\Models\bantuan_relawan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportBantuanRelawan implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return bantuan_relawan|null
     */
    public function model(array $row)
    {
        return new bantuan_relawan([
            'jenis_bantuan' =>  $row['jenis_bantuan'],
            'tanggal' =>  $row['tanggal'],
            'sasaran' =>  $row['sasaran'],
            'harga_satuan' =>  $row['harga_satuan'],
            'jumlah_penerima' =>  $row['jumlah_penerima'],
            'jumlah_bantuan' =>  $row['jumlah_bantuan'],
            'relawan_id' =>  $row['relawan_id']
        ]);
    }
}
