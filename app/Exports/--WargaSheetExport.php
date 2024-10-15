<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class WargaSheetExport implements FromArray, WithHeadings
{
    protected $data;
    protected $sheetName;

    public function __construct(array $data, string $sheetName)
    {
        $this->data = $data;
        $this->sheetName = $sheetName;
    }

    public function array(): array
    {
        // Return the data to be displayed in this sheet
        return $this->data;
    }

    public function headings(): array
    {
        // Define the headings for the sheet
        return [
            'NIK',
            'Nama',
            'Alamat',
            'Kategori Warga',
            'Kabupaten',
            'Kecamatan',
            'Kelurahan',
            'PJ ID',
            'Created At',
        ];
    }
}
