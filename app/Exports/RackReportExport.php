<?php

namespace App\Exports;

use App\Models\Rack;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class RackReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $racks;

    public function __construct($racks)
    {
        $this->racks = $racks;
    }

    public function collection()
    {
        return $this->racks;
    }

    public function headings(): array
    {
        return [
            'No',
            'Rack',
            'Total Unit',
            'Used Unit',
            'Available Unit',
            'Utilization (%)',
            'Total Device',
            'Online Devices',
            'Offline Devices',
            'Status Rack',
        ];
    }

    public function map($rack): array
    {
        static $index = 0;
        $index++;

        $devices = $rack->devices;
        $usedUnits = $devices->sum('height_units');
        $onlineDevices = $devices->where('status', 'online')->count();
        $offlineDevices = $devices->where('status', 'offline')->count();
        $availableUnits = $rack->total_units - $usedUnits;
        $utilization = $rack->total_units > 0 ? round(($usedUnits / $rack->total_units) * 100, 2) : 0;

        return [
            $index,
            $rack->name,
            $rack->total_units,
            $usedUnits,
            $availableUnits,
            $utilization,
            $devices->count(),
            $onlineDevices,
            $offlineDevices,
            ucfirst($rack->status_online ?? 'offline'),
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
