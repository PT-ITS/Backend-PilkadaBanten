<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithTitle;

class BantuanRelawanExport implements WithMultipleSheets
{
    protected $relawans;

    public function __construct($relawans)
    {
        $this->relawans = $relawans;
    }

    public function sheets(): array
    {
        $sheets = [];

        // First sheet for all relawans
        $sheets[] = new RelawanSheetExport($this->relawans, 'Relawan');

        // Create separate sheets for each unique 'sasaran'
        $sasarans = $this->relawans->flatMap(function ($relawan) {
            return $relawan->bantuanRelawans->pluck('sasaran')->unique();
        })->unique();

        $sheetNames = [
            'warga' => 'Warga',
            'ketua rt' => 'RT',
            'ketua rw' => 'RW',
            'pemuka agama' => 'Pemuka Agama',
        ];

        foreach ($sasarans as $sasaran) {
            $filteredData = $this->relawans->flatMap(function ($relawan) use ($sasaran) {
                return $relawan->bantuanRelawans->where('sasaran', $sasaran)->map(function ($bantuanRelawan) use ($relawan) {
                    return [
                        'nama_relawan' => $relawan->nama,
                        'jenis_bantuan' => $bantuanRelawan->jenis_bantuan,
                        'tanggal' => $bantuanRelawan->tanggal,
                        'harga_satuan' => $bantuanRelawan->harga_satuan,
                        'jumlah_penerima' => $bantuanRelawan->jumlah_penerima,
                        'jumlah_bantuan' => $bantuanRelawan->jumlah_bantuan,
                    ];
                });
            });

            // Use the mapped sheet names if available, or default to the 'sasaran' name
            $sheetName = $sheetNames[strtolower($sasaran)] ?? ucfirst($sasaran);
            $sheets[] = new BantuanRelawanSheetExport($filteredData, $sheetName);
        }

        return $sheets;
    }
}

class RelawanSheetExport implements FromCollection, WithHeadings, WithTitle
{
    protected $relawans;
    protected $sheetName;

    public function __construct($relawans, $sheetName = 'Relawan')
    {
        $this->relawans = $relawans;
        $this->sheetName = $sheetName;
    }

    public function collection()
    {
        return $this->relawans->map(function ($relawan) {
            return [
                'nik' => $relawan->nik,
                'nama' => $relawan->nama,
                'alamat' => $relawan->alamat,
                'kota' => $relawan->kota,
                'kec' => $relawan->kec,
                'kel' => $relawan->kel,
                'rt_rw' => $relawan->rt_rw,
                'jumlah_data' => $relawan->jumlah_data,
            ];
        });
    }

    public function headings(): array
    {
        return ['NIK', 'Nama', 'Alamat', 'Kota', 'Kecamatan', 'Kelurahan', 'RT/RW', 'Jumlah Data'];
    }

    public function title(): string
    {
        return $this->sheetName;
    }
}

class BantuanRelawanSheetExport implements FromCollection, WithHeadings, WithTitle
{
    protected $data;
    protected $sheetName;

    public function __construct($data, $sheetName)
    {
        $this->data = $data;
        $this->sheetName = $sheetName;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return ['Nama Relawan', 'Jenis Bantuan', 'Tanggal', 'Harga Satuan', 'Jumlah Penerima', 'Jumlah Bantuan'];
    }

    public function title(): string
    {
        return $this->sheetName;
    }
}
