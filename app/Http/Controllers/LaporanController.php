<?php

namespace App\Http\Controllers;

use App\Models\Monitoring;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
    $monthInput = $request->input('bulan', Carbon::now()->format('Y-m'));
    $monthDate  = Carbon::createFromFormat('Y-m', $monthInput);

    $query = Monitoring::query();
    if ($request->filled('aplikasi')) {
        $query->where('nama_aplikasi', 'like', '%' . $request->aplikasi . '%');
    }

    if ($request->filled('bulan')) {
        $query->whereYear('tanggal', $monthDate->year)
              ->whereMonth('tanggal', $monthDate->month);
    }

    if ($request->filled('tanggal_dari')) {
        $query->whereDate('tanggal', '>=', $request->tanggal_dari);
    }

    if ($request->filled('tanggal_sampai')) {
        $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
    }
// Pagination dengan 50 item per halaman    
    $monitorings = $query
    ->orderBy('tanggal', 'desc')
    ->paginate(50)
    ->withQueryString();

    return view('laporan.index', [
        'monitorings' => $monitorings,
        'bulan'       => $monthDate->format('Y-m'),
    ]);
    }

    public function pdf(Request $request)
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
            $query->whereYear('tanggal', $year);
            $periodLabel = 'Tahun ' . $year;
            $filename .= $year;
        } elseif ($periodType === 'minggu') {
            $weekStart = $request->input('minggu_dari');
            $weekEnd = $request->input('minggu_sampai');
            
            if ($weekStart && $weekEnd) {
                $query->whereDate('tanggal', '>=', $weekStart)
                      ->whereDate('tanggal', '<=', $weekEnd);
                $periodLabel = 'Minggu ' . Carbon::parse($weekStart)->format('d/m/Y') . ' - ' . Carbon::parse($weekEnd)->format('d/m/Y');
                $filename .= Carbon::parse($weekStart)->format('Y-m-d') . '_' . Carbon::parse($weekEnd)->format('Y-m-d');
            } else {
                // Default: minggu ini
                $startOfWeek = Carbon::now()->startOfWeek();
                $endOfWeek = Carbon::now()->endOfWeek();
                $query->whereDate('tanggal', '>=', $startOfWeek)
                      ->whereDate('tanggal', '<=', $endOfWeek);
                $periodLabel = 'Minggu ' . $startOfWeek->format('d/m/Y') . ' - ' . $endOfWeek->format('d/m/Y');
                $filename .= $startOfWeek->format('Y-m-d') . '_' . $endOfWeek->format('Y-m-d');
            }
        } else {
            // Default: bulan
            $monthInput = $request->input('bulan', Carbon::now()->format('Y-m'));
            $monthDate = Carbon::createFromFormat('Y-m', $monthInput);
            $query->whereYear('tanggal', $monthDate->year)
                  ->whereMonth('tanggal', $monthDate->month);
            
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
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }

        $monitorings = $query->orderBy('tanggal', 'desc')->get();

        $pdf = Pdf::loadView('laporan.pdf', [
            'monitorings' => $monitorings,
            'periodLabel' => $periodLabel,
            'periodType' => $periodType,
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
            $query->whereYear('tanggal', $year);
            $periodLabel = 'Tahun ' . $year;
            $filename .= $year;
        } elseif ($periodType === 'minggu') {
            $weekStart = $request->input('minggu_dari');
            $weekEnd = $request->input('minggu_sampai');
            
            if ($weekStart && $weekEnd) {
                $query->whereDate('tanggal', '>=', $weekStart)
                      ->whereDate('tanggal', '<=', $weekEnd);
                $periodLabel = 'Minggu ' . Carbon::parse($weekStart)->format('d/m/Y') . ' - ' . Carbon::parse($weekEnd)->format('d/m/Y');
                $filename .= Carbon::parse($weekStart)->format('Y-m-d') . '_' . Carbon::parse($weekEnd)->format('Y-m-d');
            } else {
                // Default: minggu ini
                $startOfWeek = Carbon::now()->startOfWeek();
                $endOfWeek = Carbon::now()->endOfWeek();
                $query->whereDate('tanggal', '>=', $startOfWeek)
                      ->whereDate('tanggal', '<=', $endOfWeek);
                $periodLabel = 'Minggu ' . $startOfWeek->format('d/m/Y') . ' - ' . $endOfWeek->format('d/m/Y');
                $filename .= $startOfWeek->format('Y-m-d') . '_' . $endOfWeek->format('Y-m-d');
            }
        } else {
            // Default: bulan
            $monthInput = $request->input('bulan', Carbon::now()->format('Y-m'));
            $monthDate = Carbon::createFromFormat('Y-m', $monthInput);
            $query->whereYear('tanggal', $monthDate->year)
                  ->whereMonth('tanggal', $monthDate->month);
            
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
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }

        $monitorings = $query->orderBy('tanggal', 'desc')->get();

        return Excel::download(new LaporanExport($monitorings, $periodLabel), $filename . '.xlsx');
    }
}
