<?php

namespace App\Exports;

use App\Models\MaintenanceChecklist;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class MaintenanceChecklistReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $checklists;

    public function __construct($checklists)
    {
        $this->checklists = $checklists;
    }

    public function collection()
    {
        return $this->checklists;
    }

    public function headings(): array
    {
        return [
            'No',
            'Perangkat',
            'Expired Date',
            'Status Q1',
            'Status Q2',
            'Status Q3',
            'Status Q4',
            'Tanggal Q1',
            'Tanggal Q2',
            'Tanggal Q3',
            'Tanggal Q4',
            'Keterangan',
        ];
    }

    public function map($checklist): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            $checklist->perangkat,
            optional($checklist->expired_date)->format('Y-m-d'),
            ucfirst($checklist->status_q1),
            ucfirst($checklist->status_q2),
            ucfirst($checklist->status_q3),
            ucfirst($checklist->status_q4),
            optional($checklist->tanggal_q1)->format('Y-m-d'),
            optional($checklist->tanggal_q2)->format('Y-m-d'),
            optional($checklist->tanggal_q3)->format('Y-m-d'),
            optional($checklist->tanggal_q4)->format('Y-m-d'),
            $checklist->keterangan,
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
