<?php

namespace App\Exports;

use App\Models\DeviceLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class DeviceLogExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $logs;
    protected $deviceName;

    public function __construct($logs, $deviceName = '')
    {
        $this->logs = $logs;
        $this->deviceName = $deviceName;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->logs;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Waktu',
            'Tanggal',
            'Jam',
            'Nama Perangkat',
            'IP Address',
            'Status',
            'Status Sebelumnya',
            'Keterangan',
        ];
    }

    /**
     * @param mixed $log
     * @return array
     */
    public function map($log): array
    {
        static $index = 0;
        $index++;

        $device = $log->device;

        return [
            $index,
            $log->logged_at->format('d/m/Y H:i:s'),
            $log->logged_at->format('d/m/Y'),
            $log->logged_at->format('H:i:s'),
            $device->name ?? '-',
            $device->ip_address ?? '-',
            ucfirst($log->status),
            $log->previous_status ? ucfirst($log->previous_status) : '-',
            $log->message ?? '-',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2c2f7e']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }
}
