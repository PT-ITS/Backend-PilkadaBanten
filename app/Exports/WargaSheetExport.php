<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class WargaSheetExport implements WithMultipleSheets
{
    protected $successData;
    protected $failedData;

    public function __construct(array $successData, array $failedData)
    {
        $this->successData = $successData;
        $this->failedData = $failedData;
    }

    // Fungsi untuk mengelola beberapa sheet
    public function sheets(): array
    {
        return [
            // Sheet untuk Success Data (hanya menampilkan NIK, Nama, Jenis Kelamin, Alamat)
            new class($this->successData, 'Success Data') implements FromArray, WithHeadings, WithTitle {
                protected $data;
                protected $sheetName;

                public function __construct(array $data, string $sheetName)
                {
                    $this->data = $data;
                    $this->sheetName = $sheetName;
                }

                public function array(): array
                {
                    // Return hanya data yang dibutuhkan untuk success data
                    return array_map(function ($data) {
                        return [
                            'nik' => $data['nik'],
                            'nama' => $data['nama'],
                            'jenis_kelamin' => $data['jenis_kelamin'],
                            'kategori' => $data['kategori_warga'],
                            'alamat' => $data['alamat'],
                        ];
                    }, $this->data);
                }

                public function headings(): array
                {
                    return [
                        'NIK',
                        'Nama',
                        'Jenis Kelamin',
                        'Kategori',
                        'Alamat',
                    ];
                }

                public function title(): string
                {
                    return $this->sheetName;
                }
            },

            // Sheet untuk Failed Data (Dengan Nama PJ)
            new class($this->failedData, 'Failed Data') implements FromArray, WithHeadings, WithTitle {
                protected $data;
                protected $sheetName;

                public function __construct(array $data, string $sheetName)
                {
                    $this->data = $data;
                    $this->sheetName = $sheetName;
                }

                public function array(): array
                {
                    return array_map(function ($data) {
                        // Ambil nama PJ dari relasi user berdasarkan pj_id
                        $pj = User::find($data['pj_id']);
                        return [
                            'nik' => $data['nik'],
                            'nama' => $data['nama'],
                            'alamat' => $data['alamat'],
                            'nama_pj' => $pj ? $pj->name : 'Unknown',  // Jika pj_id tidak ditemukan, tampilkan 'Unknown'
                            'created_at' => $data['created_at'],
                        ];
                    }, $this->data);
                }

                public function headings(): array
                {
                    return [
                        'NIK',
                        'Nama',
                        'Alamat',
                        'Nama PJ',  // Nama PJ diambil dari relasi dengan tabel User
                        'Created At',
                    ];
                }

                public function title(): string
                {
                    return $this->sheetName;
                }
            }
        ];
    }
}
