<?php

namespace App\Exports;

use App\Models\ChecklistExpiredDate;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ChecklistExpiredDateExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $items;

    public function __construct($items)
    {
        $this->items = $items;
    }

    public function collection()
    {
        return $this->items;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Perangkat',
            'Tanggal Kadaluarsa',
            'Status',
            'Sisa Hari',
            'Peringatan',
            'Keterangan',
        ];
    }

    public function map($item): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            $item->nama_perangkat,
            $item->tanggal_kadaluarsa ? Carbon::parse($item->tanggal_kadaluarsa)->format('d/m/Y') : '-',
            $item->status,
            $item->sisa_hari,
            strip_tags($item->peringatan),
            $item->keterangan,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0A978E'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }
}
