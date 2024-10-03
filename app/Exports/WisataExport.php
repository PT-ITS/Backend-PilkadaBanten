<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;

class WisataExport implements WithMultipleSheets
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function sheets(): array
    {
        $sheets = [];
        $sheetNames = [
            'hotel' => 'Hotel',
            'hiburan' => 'Hiburan',
            'fnb' => 'Fnb'
        ];
        foreach ($this->data as $key => $value) {
            $sheets[] = new WisataSheetExport(collect($value), $sheetNames[$key] ?? ucfirst($key));
        }
        return $sheets;
    }
}

class WisataSheetExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;
    protected $sheetName;
    protected $rowNumber;

    public function __construct(Collection $data, $sheetName)
    {
        $this->data = $data->filter(); // Ensure collection is not null
        $this->sheetName = $sheetName;
        $this->rowNumber = 1; // Initialize row number
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        // Ensure collection is not empty
        if ($this->data->isEmpty()) {
            return [];
        }

        // Get the first item to extract the keys (excluding 'id')
        $firstItem = $this->data->first();
        if ($firstItem === null) {
            return [];
        }

        $keys = array_keys($firstItem->toArray());
        $keys = array_diff($keys, ['id']);

        return array_merge(['No'], $keys);
    }

    public function map($row): array
    {
        // Handle null rows
        if ($row === null) {
            return array_merge([$this->rowNumber++], []);
        }

        // Remove 'id' from the row
        $rowArray = $row->toArray();
        unset($rowArray['id']);

        // Add row number (starts from 1)
        $rowArray = array_merge([$this->rowNumber++], $rowArray);

        return $rowArray;
    }

    public function title(): string
    {
        return $this->sheetName;
    }
}
