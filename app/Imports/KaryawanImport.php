<?php

namespace App\Imports;

use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KaryawanImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return Karyawan|null
     */
    public function model(array $row)
    {
        return new Karyawan([
            'namaKaryawan'     => $row['namakaryawan'],
            'jabatanKaryawan'    => $row['jabatankaryawan'],
            'alamatKaryawan'    => $row['alamatkaryawan'],
            'jenisKelamin'    => $row['jeniskelamin'],
            'wargaNegara'    => $row['warganegara'],
            'sertifikasiKaryawan'    => $row['sertifikasikaryawan'],
            'pendidikanKaryawan'    => $row['pendidikankaryawan'],
        ]);
    }
}
