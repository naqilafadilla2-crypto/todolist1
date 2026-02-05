<?php

namespace App\Exports;

use App\Models\Monitoring;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class LaporanExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $monitorings;
    protected $periodLabel;

    public function __construct($monitorings, $periodLabel = '')
    {
        $this->monitorings = $monitorings;
        $this->periodLabel = $periodLabel;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->monitorings;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama Aplikasi',
            'Status',
            'Tanggal',
            'Deskripsi',
            'Jumlah Lampiran',
        ];
    }

    /**
     * @param mixed $monitoring
     * @return array
     */
    public function map($monitoring): array
    {
        static $index = 0;
        $index++;

        $files = $monitoring->file ? json_decode($monitoring->file, true) : [];
        if (!is_array($files)) {
            $files = $monitoring->file ? [$monitoring->file] : [];
        }

        return [
            $index,
            $monitoring->nama_aplikasi,
            ucfirst($monitoring->status),
            $monitoring->tanggal ? Carbon::parse($monitoring->tanggal)->format('d/m/Y') : '-',
            $monitoring->deskripsi ?? '-',
            count($files),
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
