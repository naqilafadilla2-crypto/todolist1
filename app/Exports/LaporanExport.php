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

use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class LaporanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithDrawings
{
    protected $monitorings;
    protected $periodLabel;
    protected $chartImage;
    protected $totalHijau;
    protected $totalKuning;
    protected $totalMerah;

    public function __construct($monitorings, $periodLabel = '', $chartImage = null, $totalHijau = 0, $totalKuning = 0, $totalMerah = 0)
    {
        $this->monitorings = $monitorings;
        $this->periodLabel = $periodLabel;
        $this->chartImage = $chartImage;
        $this->totalHijau = $totalHijau;
        $this->totalKuning = $totalKuning;
        $this->totalMerah = $totalMerah;
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

    /**
     * Add chart image as a drawing to the sheet
     *
     * @return Drawing[]
     */
    public function drawings()
    {
        if (! $this->chartImage) {
            return [];
        }

        // strip data URI prefix if present
        $base64 = preg_replace('#^data:image/\w+;base64,#i', '', $this->chartImage);
        $imageData = base64_decode($base64);
        $tmp = sys_get_temp_dir() . '/chart_' . uniqid() . '.png';
        file_put_contents($tmp, $imageData);

        $drawing = new Drawing();
        $drawing->setName('Grafik Pantau');
        $drawing->setDescription('Grafik status 30 hari');
        $drawing->setPath($tmp);
        $drawing->setHeight(200);
        // place near top right corner
        $drawing->setCoordinates('H2');

        return [$drawing];
    }
}
