<?php

namespace App\Imports;

use App\Models\dukungan_tokoh;
use App\Models\jenis_dukungan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ImportDukunganTokoh implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return dukungan_tokoh|null
     */
    public function model(array $row)
    {
        $dukunganTokoh = new dukungan_tokoh([
            'pelaksana'     => $row['pelaksana'],
            'tanggal'    => is_numeric($row['tanggal']) ? Date::excelToDateTimeObject($row['tanggal'])->format('Y-m-d') : $row['tanggal'],
            'lokasi'    => $row['lokasi'],
            'sasaran'    => $row['sasaran'],
            'penanggung_jawab'    => $row['penanggung_jawab'],
        ]);

        $dukunganTokoh->save();

        // Save related jenis_dukungan entries
        for ($i = 1; isset($row['jenis_dukungan_' . $i]); $i++) {
            $jenisBarang = new jenis_dukungan();
            $jenisBarang->jenis_dukungan = $row['jenis_dukungan_' . $i];
            $jenisBarang->jumlah = $row['jumlah_' . $i];
            $jenisBarang->dukungan_tokoh_id = $dukunganTokoh->id;
            $jenisBarang->save();
        }

        return $dukunganTokoh;
    }
}
