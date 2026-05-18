<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChecklistExpiredDate;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ChecklistExpiredDateExport;

class ChecklistExpiredDateController extends Controller
{
    /**
     * Display data
     */
    public function index()
    {
        $data = ChecklistExpiredDate::orderByDesc('created_at')->get();

        return view('checklist.index', compact('data'));
    }

    /**
     * Build query for checklist expired date report
     */
    private function buildChecklistQuery(Request $request)
    {
        $query = ChecklistExpiredDate::query();

        if ($request->filled('nama_perangkat')) {
            $query->where('nama_perangkat', 'like', '%' . $request->nama_perangkat . '%');
        }

        $hasDateRange = $request->filled('tanggal_dari') || $request->filled('tanggal_sampai');

        if (!$hasDateRange && $request->filled('periode')) {
            $periode = $request->periode;
            $timezone = 'Asia/Jakarta';
            $today = Carbon::now($timezone)->startOfDay();

            if ($periode === 'hari') {
                $from = $today;
                $to = $today;
            } elseif ($periode === 'minggu') {
                $from = $today->copy()->startOfWeek(Carbon::MONDAY);
                $to = $today->copy()->endOfWeek(Carbon::SUNDAY);
            } elseif ($periode === 'bulan') {
                $from = $today->copy()->startOfMonth();
                $to = $today->copy()->endOfMonth();
            } elseif ($periode === 'tahun') {
                $year = $request->filled('tahun') ? $request->tahun : $today->year;
                $from = Carbon::createFromDate($year, 1, 1, $timezone)->startOfDay();
                $to = Carbon::createFromDate($year, 12, 31, $timezone)->endOfDay();
            }

            if (isset($from) && isset($to)) {
                $query->whereDate('tanggal_kadaluarsa', '>=', $from->format('Y-m-d'))
                      ->whereDate('tanggal_kadaluarsa', '<=', $to->format('Y-m-d'));
            }
        }

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_kadaluarsa', '>=', $request->tanggal_dari, 'and');
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_kadaluarsa', '<=', $request->tanggal_sampai, 'and');
        }

        if (!$hasDateRange && $request->filled('periode') && $request->periode === 'tahun' && $request->filled('tahun')) {
            // Already handled in periode filter above.
        }

        return $query;
    }

    /**
     * Report checklist expired date
     */
    public function report(Request $request)
    {
        $query = $this->buildChecklistQuery($request);
        $data = $query->orderByDesc('created_at')->get();
        $total = $data->count();
        $hijau = $data->where('status', 'HIJAU')->count();
        $kuning = $data->where('status', 'KUNING')->count();
        $merah = $data->where('status', 'MERAH')->count();
        $expired = $data->filter(function ($item) {
            return Carbon::parse($item->tanggal_kadaluarsa)->isPast();
        })->count();

        return view('checklist.report', compact(
            'data',
            'total',
            'hijau',
            'kuning',
            'merah',
            'expired'
        ))->with([
            'filterNama' => $request->nama_perangkat,
            'filterTanggalDari' => $request->tanggal_dari,
            'filterTanggalSampai' => $request->tanggal_sampai,
        ]);
    }

    /**
     * Download report as PDF
     */
    public function pdf(Request $request)
    {
        $query = $this->buildChecklistQuery($request);
        $data = $query->orderByDesc('created_at')->get();
        $total = $data->count();
        $hijau = $data->where('status', 'HIJAU')->count();
        $kuning = $data->where('status', 'KUNING')->count();
        $merah = $data->where('status', 'MERAH')->count();

        $pdf = Pdf::loadView('checklist.report-pdf', [
            'data' => $data,
            'total' => $total,
            'hijau' => $hijau,
            'kuning' => $kuning,
            'merah' => $merah,
            'filterNama' => $request->nama_perangkat,
            'filterTanggalDari' => $request->tanggal_dari,
            'filterTanggalSampai' => $request->tanggal_sampai,
        ]);

        return $pdf->download('laporan-checklist-expired-date-' . now()->format('YmdHis') . '.pdf');
    }

    /**
     * Download report as Excel
     */
    public function excel(Request $request)
    {
        $query = $this->buildChecklistQuery($request);
        $data = $query->orderByDesc('created_at')->get();

        return Excel::download(
            new ChecklistExpiredDateExport($data),
            'laporan-checklist-expired-date-' . now()->format('YmdHis') . '.xlsx'
        );
    }

    /**
     * Form create
     */
    public function create()
    {
        return view('checklist.create');
    }

    /**
     * Store data
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_perangkat' => 'required',
            'tanggal_kadaluarsa' => 'required|date',
            'keterangan' => 'nullable',
        ]);

        $status = ChecklistExpiredDate::generateStatus(
            $request->tanggal_kadaluarsa
        );

        ChecklistExpiredDate::create([
            'nama_perangkat' => $request->nama_perangkat,
            'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
            'status' => $status,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()
            ->route('checklist.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Form edit
     */
    public function edit($id)
    {
        $item = ChecklistExpiredDate::findOrFail($id);

        return view('checklist.edit', compact('item'));
    }

    /**
     * Update data
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_perangkat' => 'required',
            'tanggal_kadaluarsa' => 'required|date',
            'keterangan' => 'nullable',
        ]);

        $item = ChecklistExpiredDate::findOrFail($id);
        $item->nama_perangkat = $request->nama_perangkat;
        $item->tanggal_kadaluarsa = $request->tanggal_kadaluarsa;
        $item->status = ChecklistExpiredDate::generateStatus($request->tanggal_kadaluarsa);
        $item->keterangan = $request->keterangan;
        $item->save();

        return redirect()
            ->route('checklist.index')
            ->with('success', 'Data berhasil diperbarui');
    }

    /**
     * Hapus data
     */
    public function destroy($id)
    {
        $item = ChecklistExpiredDate::findOrFail($id);
        $item->delete();

        return redirect()
            ->route('checklist.index')
            ->with('success', 'Data berhasil dihapus');
    }
}