<?php

namespace App\Http\Controllers;

use App\Models\Monitoring;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

    $monitorings = $query
        ->orderBy('tanggal', 'desc')
        ->get();

    return view('laporan.index', [
        'monitorings' => $monitorings,
        'bulan'       => $monthDate->format('Y-m'),
    ]);
    }

    public function pdf(Request $request)
    {
        $monthInput = $request->input('bulan', Carbon::now()->format('Y-m'));
        $monthDate = Carbon::parse($monthInput . '-01');

        $monitorings = Monitoring::whereYear('tanggal', $monthDate->year)
            ->whereMonth('tanggal', $monthDate->month)
            ->orderBy('tanggal', 'desc')
            ->get();

        $pdf = Pdf::loadView('laporan.pdf', [
            'monitorings' => $monitorings,
            'bulan' => $monthDate,
        ]);

        return $pdf->download('laporan-' . $monthDate->format('Y-m') . '.pdf');
    }
}
