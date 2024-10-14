<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\User;

class FailedWargaExport implements FromArray, WithHeadings
{
    protected $failedData;

    public function __construct(array $failedData)
    {
        $this->failedData = $failedData;
    }

    // Fungsi untuk menyediakan data yang akan diekspor
    public function array(): array
    {
        // Loop melalui data yang gagal dan tambahkan PJ dan created_at
        return array_map(function($data) {
            // Ambil nama PJ berdasarkan ID
            $pj = User::find($data['pj_id']);
            return [
                'nik' => $data['nik'],                   // NIK
                'nama' => $data['nama'],                 // Nama
                'alamat' => $data['alamat'],             // Alamat
                'nama_pj' => $pj ? $pj->name : 'Unknown', // Nama PJ
                'created_at' => now()->toDateTimeString(), // Created At
            ];
        }, $this->failedData);
    }

    // Fungsi untuk menentukan heading (judul kolom)
    public function headings(): array
    {
        return [
            'NIK',          // Kolom untuk NIK
            'Nama',         // Kolom untuk Nama
            'Alamat',       // Kolom untuk Alamat
            'Nama PJ',      // Kolom untuk nama penanggung jawab
            'Created At',   // Kolom untuk created_at
        ];
    }
}
