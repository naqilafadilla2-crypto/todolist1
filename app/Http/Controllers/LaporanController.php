<?php

namespace App\Http\Controllers;

use App\Models\Monitoring;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
    $monthInput = $request->input('bulan', Carbon::now()->format('Y-m'));
    $monthDate  = Carbon::createFromFormat('Y-m', $monthInput);

    // base query untuk laporan & grafik (tanpa order/pagination)
    $baseQuery = Monitoring::query();
    
    // Filter aplikasi jika ada
    if ($request->filled('aplikasi')) {
        $baseQuery->where('nama_aplikasi', 'like', '%' . $request->aplikasi . '%');
    }

    // Filter bulan (default bulan ini)
    $baseQuery->where(DB::raw('YEAR(tanggal)'), '=', $monthDate->year)
              ->where(DB::raw('MONTH(tanggal)'), '=', $monthDate->month);

    // Filter tanggal range jika ada (optional)
    if ($request->filled('tanggal_dari')) {
        $baseQuery->where(DB::raw('DATE(tanggal)'), '>=', $request->tanggal_dari);
    }

    if ($request->filled('tanggal_sampai')) {
        $baseQuery->where(DB::raw('DATE(tanggal)'), '<=', $request->tanggal_sampai);
    }
    
    // Pagination dengan 50 item per halaman    
    $monitorings = (clone $baseQuery)
        ->orderBy('tanggal', 'desc')
        ->paginate(50)
        ->withQueryString();

    // ===== DATA GRAFIK: sumbu X hari 1-30, sumbu Y = jumlah merah/kuning/hijau per hari =====
    $chartLabels = range(1, 30);
    $chartHijau  = [];
    $chartKuning = [];
    $chartMerah  = [];

    // ambil agregat jumlah per hari & status dari data hasil filter
    $rows = (clone $baseQuery)
        ->selectRaw("DATE(tanggal) as day, status, COUNT(*) as total", [])
        ->groupBy(DB::raw("DATE(tanggal)"), 'status')
        ->get();

    // siapkan map hari (berdasarkan awal bulan yang dipilih) -> count per warna
    $start = $monthDate->copy()->startOfMonth();
    $end = $monthDate->copy()->endOfMonth();
    
    // Hitung jumlah hari dalam bulan
    $daysInMonth = $end->day;
    
    $dayMap = [];
    for ($i = 0; $i < $daysInMonth; $i++) {
        $dayKey = $start->copy()->addDays($i)->toDateString();
        $dayMap[$dayKey] = ['hijau' => 0, 'kuning' => 0, 'merah' => 0];
    }

    foreach ($rows as $r) {
        $dayKey = $r->day;
        if (!isset($dayMap[$dayKey])) {
            continue;
        }
        if (in_array($r->status, ['hijau', 'kuning', 'merah'], true)) {
            $dayMap[$dayKey][$r->status] = (int) $r->total;
        }
    }

    // rakit data berdasarkan jumlah hari dalam bulan
    for ($i = 0; $i < $daysInMonth; $i++) {
        $dayKey = $start->copy()->addDays($i)->toDateString();
        $chartHijau[]  = $dayMap[$dayKey]['hijau'];
        $chartKuning[] = $dayMap[$dayKey]['kuning'];
        $chartMerah[]  = $dayMap[$dayKey]['merah'];
    }

    $namaAplikasiGrafik = $request->filled('aplikasi') ? $request->aplikasi : 'Semua Aplikasi';
    $chartTitle = 'Grafik Pantau ' . $namaAplikasiGrafik . ' ' . $monthDate->translatedFormat('F Y');

    // Hitung total dari grafik data (sama dengan tabel)
    $totalHijau = array_sum($chartHijau);
    $totalKuning = array_sum($chartKuning);
    $totalMerah = array_sum($chartMerah);

    return view('laporan.index', [
        'monitorings' => $monitorings,
        'bulan'       => $monthDate->format('Y-m'),
        'chartLabels' => range(1, $daysInMonth),
        'chartHijau'  => $chartHijau,
        'chartKuning' => $chartKuning,
        'chartMerah'  => $chartMerah,
        'chartTitle'  => $chartTitle,
        'totalHijau'  => $totalHijau,
        'totalKuning' => $totalKuning,
        'totalMerah'  => $totalMerah,
    ]);
    }
public function pdf(Request $request)
{
    // Tambahan untuk handle data banyak
    ini_set('memory_limit', '1024M');
    set_time_limit(300);

    $query = Monitoring::query();
    $periodType = $request->input('periode', 'bulan'); // tahun, bulan, minggu
    $periodLabel = '';
    $filename = 'laporan-';

    // Filter berdasarkan aplikasi jika ada
    if ($request->filled('aplikasi')) {
        $query->where('nama_aplikasi', 'like', '%' . $request->aplikasi . '%');
    }

    // Filter berdasarkan periode
    if ($periodType === 'tahun') {

        $year = $request->input('tahun', Carbon::now()->year);
        $query->where(DB::raw('YEAR(tanggal)'), '=', $year);

        $periodLabel = 'Tahun ' . $year;
        $filename .= $year;

    } elseif ($periodType === 'minggu') {

        $weekStart = $request->input('minggu_dari');
        $weekEnd = $request->input('minggu_sampai');

        if ($weekStart && $weekEnd) {

            $query->where(DB::raw('DATE(tanggal)'), '>=', $weekStart)
                  ->where(DB::raw('DATE(tanggal)'), '<=', $weekEnd);

            $periodLabel = 'Minggu ' .
                Carbon::parse($weekStart)->format('d/m/Y') .
                ' - ' .
                Carbon::parse($weekEnd)->format('d/m/Y');

            $filename .= Carbon::parse($weekStart)->format('Y-m-d') .
                        '_' .
                        Carbon::parse($weekEnd)->format('Y-m-d');

        } else {

            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();

            $query->where(DB::raw('DATE(tanggal)'), '>=', $startOfWeek)
                  ->where(DB::raw('DATE(tanggal)'), '<=', $endOfWeek);

            $periodLabel = 'Minggu ' .
                $startOfWeek->format('d/m/Y') .
                ' - ' .
                $endOfWeek->format('d/m/Y');

            $filename .= $startOfWeek->format('Y-m-d') .
                        '_' .
                        $endOfWeek->format('Y-m-d');
        }

    } elseif ($periodType === 'hari') {

        $tanggal = $request->input('tanggal', Carbon::today()->toDateString());
        $query->where(DB::raw('DATE(tanggal)'), '=', $tanggal);

        $periodLabel = 'Hari ' . Carbon::parse($tanggal)->format('d/m/Y');
        $filename .= Carbon::parse($tanggal)->format('Y-m-d');

    } else {

        // Default: bulan
        $monthInput = $request->input('bulan', Carbon::now()->format('Y-m'));
        $monthDate = Carbon::createFromFormat('Y-m', $monthInput);

        $query->where(DB::raw('YEAR(tanggal)'), '=', $monthDate->year)
              ->where(DB::raw('MONTH(tanggal)'), '=', $monthDate->month);

        $bulanIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $periodLabel = $bulanIndo[$monthDate->month] . ' ' . $monthDate->year;
        $filename .= $monthDate->format('Y-m');
    }

    // Filter tanggal dari/sampai jika ada
    if ($request->filled('tanggal_dari')) {
        $query->where(DB::raw('DATE(tanggal)'), '>=', $request->tanggal_dari);
    }

    if ($request->filled('tanggal_sampai')) {
        $query->where(DB::raw('DATE(tanggal)'), '<=', $request->tanggal_sampai);
    }

    // clone query for chart data before adding order by
    $chartLabels = range(1, 30);
    $chartHijau  = [];
    $chartKuning = [];
    $chartMerah  = [];

    // hitung jumlah per hari/status berdasarkan filter yang sama
    $rowsQuery = (clone $query);
    // strip any orderBy clauses which would conflict with groupBy
    $rowsQuery->getQuery()->orders = null;
    $rows = $rowsQuery
        ->selectRaw("DATE(COALESCE(tanggal, created_at)) as day, status, COUNT(*) as total", [])
        ->groupBy(DB::raw("DATE(COALESCE(tanggal, created_at))"), 'status')
        ->get();

    // now fetch monitorings with ordering for listing
    $monitorings = (clone $query)
        ->orderBy('tanggal', 'desc')
        ->get();

    // 🔥 Cek kalau data kosong
    if ($monitorings->isEmpty()) {
        return back()->with('error', 'Data tidak ditemukan untuk periode ini.');
    }

    $start = Carbon::now();
    if ($periodType === 'tahun') {
        $start = Carbon::createFromFormat('Y', $year)->startOfYear();
    } elseif ($periodType === 'minggu') {
        $start = Carbon::parse($weekStart ?? now());
    } elseif ($periodType === 'hari') {
        $start = Carbon::parse($tanggal);
    } else {
        // bulan
        $monthDate = Carbon::createFromFormat('Y-m', $monthInput ?? Carbon::now()->format('Y-m'));
        $start = $monthDate->copy()->startOfMonth();
    }

    $dayMap = [];
    for ($i = 0; $i < 30; $i++) {
        $dayKey = $start->copy()->addDays($i)->toDateString();
        $dayMap[$dayKey] = ['hijau' => 0, 'kuning' => 0, 'merah' => 0];
    }

    foreach ($rows as $r) {
        $dayKey = $r->day;
        if (!isset($dayMap[$dayKey])) {
            continue;
        }
        if (in_array($r->status, ['hijau', 'kuning', 'merah'], true)) {
            $dayMap[$dayKey][$r->status] = (int) $r->total;
        }
    }
    for ($i = 0; $i < 30; $i++) {
        $dayKey = $start->copy()->addDays($i)->toDateString();
        $chartHijau[]  = $dayMap[$dayKey]['hijau'];
        $chartKuning[] = $dayMap[$dayKey]['kuning'];
        $chartMerah[]  = $dayMap[$dayKey]['merah'];
    }

    // generate base64 image via QuickChart
    $qcConfig = [
        'type' => 'line',
        'data' => [
            'labels' => $chartLabels,
            'datasets' => [
                ['label' => 'Hijau', 'data' => $chartHijau, 'borderColor' => '#2ecc71', 'fill' => false, 'tension' => 0, 'stepped' => true],
                ['label' => 'Kuning', 'data' => $chartKuning, 'borderColor' => '#f1c40f', 'fill' => false, 'tension' => 0, 'stepped' => true],
                ['label' => 'Merah', 'data' => $chartMerah, 'borderColor' => '#e74c3c', 'fill' => false, 'tension' => 0, 'stepped' => true],
            ],
        ],
        'options' => [
            'plugins' => ['legend' => ['position' => 'top'],
                          'title' => ['display' => true, 'text' => $chartTitle ?? 'Grafik Pantau']],
            'scales' => ['y' => ['beginAtZero' => true, 'title' => ['display' => true, 'text' => 'Jumlah'] ],
                         'x' => ['title' => ['display' => true, 'text' => 'Hari'] ]],
        ],
    ];
    $qcUrl = 'https://quickchart.io/chart?c=' . urlencode(json_encode($qcConfig));
    $context = stream_context_create([
        "ssl" => [
            "verify_peer" => false,
            "verify_peer_name" => false,
        ],
    ]);
    $chartImage = 'data:image/png;base64,' . base64_encode(file_get_contents($qcUrl, false, $context));

    // Hitung total dari grafik data (konsisten dengan index)
    $totalHijau = array_sum($chartHijau);
    $totalKuning = array_sum($chartKuning);
    $totalMerah = array_sum($chartMerah);

    $pdf = Pdf::loadView('laporan.pdf', [
        'monitorings' => $monitorings,
        'periodLabel' => $periodLabel,
        'periodType' => $periodType,
        'chartImage' => $chartImage,
        'totalHijau' => $totalHijau,
        'totalKuning' => $totalKuning,
        'totalMerah' => $totalMerah,
    ]);

    return $pdf->download($filename . '.pdf');
}

    public function excel(Request $request)
    {
        $query = Monitoring::query();
        $periodType = $request->input('periode', 'bulan'); // tahun, bulan, minggu
        $periodLabel = '';
        $filename = 'laporan-';

        // Filter berdasarkan aplikasi jika ada
        if ($request->filled('aplikasi')) {
            $query->where('nama_aplikasi', 'like', '%' . $request->aplikasi . '%');
        }

        // Filter berdasarkan periode
        if ($periodType === 'tahun') {
            $year = $request->input('tahun', Carbon::now()->year);
            $query->where(DB::raw('YEAR(tanggal)'), '=', $year);
            $periodLabel = 'Tahun ' . $year;
            $filename .= $year;
        } elseif ($periodType === 'minggu') {
            $weekStart = $request->input('minggu_dari');
            $weekEnd = $request->input('minggu_sampai');
            
            if ($weekStart && $weekEnd) {
                $query->where(DB::raw('DATE(tanggal)'), '>=', $weekStart)
                      ->where(DB::raw('DATE(tanggal)'), '<=', $weekEnd);
                $periodLabel = 'Minggu ' . Carbon::parse($weekStart)->format('d/m/Y') . ' - ' . Carbon::parse($weekEnd)->format('d/m/Y');
                $filename .= Carbon::parse($weekStart)->format('Y-m-d') . '_' . Carbon::parse($weekEnd)->format('Y-m-d');
            } else {
                // Default: minggu ini
                $startOfWeek = Carbon::now()->startOfWeek();
                $endOfWeek = Carbon::now()->endOfWeek();
                $query->where(DB::raw('DATE(tanggal)'), '>=', $startOfWeek)
                      ->where(DB::raw('DATE(tanggal)'), '<=', $endOfWeek);
                $periodLabel = 'Minggu ' . $startOfWeek->format('d/m/Y') . ' - ' . $endOfWeek->format('d/m/Y');
                $filename .= $startOfWeek->format('Y-m-d') . '_' . $endOfWeek->format('Y-m-d');
            }
        } elseif ($periodType === 'hari') {
            $tanggal = $request->input('tanggal', Carbon::today()->toDateString());
            $query->where(DB::raw('DATE(tanggal)'), '=', $tanggal);
            $periodLabel = 'Hari ' . Carbon::parse($tanggal)->format('d/m/Y');
            $filename .= Carbon::parse($tanggal)->format('Y-m-d');
        } else {
            // Default: bulan
            $monthInput = $request->input('bulan', Carbon::now()->format('Y-m'));
            $monthDate = Carbon::createFromFormat('Y-m', $monthInput);
            $query->where(DB::raw('YEAR(tanggal)'), '=', $monthDate->year)
                  ->where(DB::raw('MONTH(tanggal)'), '=', $monthDate->month);
            
            // Format bulan dalam bahasa Indonesia
            $bulanIndo = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            $periodLabel = $bulanIndo[$monthDate->month] . ' ' . $monthDate->year;
            $filename .= $monthDate->format('Y-m');
        }

        // Filter tanggal dari/sampai jika ada
        if ($request->filled('tanggal_dari')) {
            $query->where(DB::raw('DATE(tanggal)'), '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->where(DB::raw('DATE(tanggal)'), '<=', $request->tanggal_sampai);
        }

        $monitorings = $query->orderBy('tanggal', 'desc')->get();

        // --- hitung data grafik sama seperti pada PDF
        $chartLabels = range(1, 30);
        $chartHijau  = [];
        $chartKuning = [];
        $chartMerah  = [];

        $rowsQuery = (clone $query);
        $rowsQuery->getQuery()->orders = null;
        $rows = $rowsQuery
            ->selectRaw("DATE(COALESCE(tanggal, created_at)) as day, status, COUNT(*) as total", [])
            ->groupBy(DB::raw("DATE(COALESCE(tanggal, created_at))"), 'status')
            ->get();

        $start = Carbon::now();
        if ($periodType === 'tahun') {
            $start = Carbon::createFromFormat('Y', $year)->startOfYear();
        } elseif ($periodType === 'minggu') {
            $start = Carbon::parse($weekStart ?? now());
        } elseif ($periodType === 'hari') {
            $start = Carbon::parse($tanggal);
        } else {
            $monthDate = Carbon::createFromFormat('Y-m', $monthInput ?? Carbon::now()->format('Y-m'));
            $start = $monthDate->copy()->startOfMonth();
        }

        $dayMap = [];
        for ($i = 0; $i < 30; $i++) {
            $dayKey = $start->copy()->addDays($i)->toDateString();
            $dayMap[$dayKey] = ['hijau' => 0, 'kuning' => 0, 'merah' => 0];
        }
        foreach ($rows as $r) {
            $dayKey = $r->day;
            if (!isset($dayMap[$dayKey])) continue;
            if (in_array($r->status, ['hijau', 'kuning', 'merah'], true)) {
                $dayMap[$dayKey][$r->status] = (int) $r->total;
            }
        }
        for ($i = 0; $i < 30; $i++) {
            $dayKey = $start->copy()->addDays($i)->toDateString();
            $chartHijau[]  = $dayMap[$dayKey]['hijau'];
            $chartKuning[] = $dayMap[$dayKey]['kuning'];
            $chartMerah[]  = $dayMap[$dayKey]['merah'];
        }

        // build quickchart image
        $qcConfig = [
            'type' => 'line',
            'data' => [
                'labels' => $chartLabels,
                'datasets' => [
                    ['label' => 'Hijau', 'data' => $chartHijau, 'borderColor' => '#2ecc71', 'fill' => false, 'tension' => 0, 'stepped' => true],
                    ['label' => 'Kuning', 'data' => $chartKuning, 'borderColor' => '#f1c40f', 'fill' => false, 'tension' => 0, 'stepped' => true],
                    ['label' => 'Merah', 'data' => $chartMerah, 'borderColor' => '#e74c3c', 'fill' => false, 'tension' => 0, 'stepped' => true],
                ],
            ],
            'options' => [
                'plugins' => ['legend' => ['position' => 'top'],
                              'title' => ['display' => true, 'text' => $chartTitle ?? 'Grafik Pantau']],
                'scales' => ['y' => [
                                    'beginAtZero' => true,
                                    'title' => ['display' => true, 'text' => 'Jumlah'],
                                    'ticks' => ['stepSize' => 1, 'precision' => 0]
                                ],
                             'x' => ['title' => ['display' => true, 'text' => 'Hari'] ]],
            ],
        ];
        $qcUrl = 'https://quickchart.io/chart?c=' . urlencode(json_encode($qcConfig));
        $context = stream_context_create([
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ],
        ]);
        $chartImage = 'data:image/png;base64,' . base64_encode(file_get_contents($qcUrl, false, $context));

        // Hitung total dari grafik data
        $totalHijau = array_sum($chartHijau);
        $totalKuning = array_sum($chartKuning);
        $totalMerah = array_sum($chartMerah);

        return Excel::download(new LaporanExport($monitorings, $periodLabel, $chartImage, $totalHijau, $totalKuning, $totalMerah), $filename . '.xlsx');
    }
}
