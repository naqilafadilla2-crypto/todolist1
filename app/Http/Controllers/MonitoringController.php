<?php

namespace App\Http\Controllers;

use App\Models\AppLink;
use App\Models\Monitoring;
use App\Models\MonitoringLog;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class MonitoringController extends Controller
{
    /**
     * Display a listing of all monitorings.
     */
   
    public function index()
    {
    // ambil jumlah data dari kelola aplikasi (applink)
    $jumlahApplink = Applink::count();

    // fallback kalau data kosong
    $perPage = $jumlahApplink > 0 ? $jumlahApplink : 1;

    $monitorings = Monitoring::orderBy('created_at', 'desc')
        ->paginate($perPage);

    // buat data chart dari 30 monitoring terakhir (urut naik berdasarkan waktu)
    $last30 = Monitoring::orderBy('created_at', 'asc')->take(30)->get();
    $chartLabels = [];
    $chartValues = [];
    $chartColors = [];

    foreach ($last30 as $m) {
        $chartLabels[] = $m->created_at->format('d');
        switch ($m->status) {
            case 'hijau':
                $chartValues[] = 3;
                $chartColors[] = '#2ecc71';
                break;
            case 'kuning':
                $chartValues[] = 2;
                $chartColors[] = '#f1c40f';
                break;
            case 'merah':
                $chartValues[] = 1;
                $chartColors[] = '#e74c3c';
                break;
            default:
                $chartValues[] = null;
                $chartColors[] = '#ccc';
        }
    }

    return view('monitoring.index', compact('monitorings', 'chartLabels', 'chartValues', 'chartColors'));
    }

    /**
     * Show the form for creating a new monitoring.
     */
    public function create()
    {
        $applinks = AppLink::orderBy('name')->get();
        return view('monitoring.create', compact('applinks'));
    }

    /**
     * Store a newly created monitoring in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'applink_id' => 'required|exists:app_links,id',
            'status' => 'required|in:hijau,kuning,merah',
            'tanggal' => 'required|date',
            'file.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        $applink = AppLink::findOrFail($request->applink_id);

        MonitoringLog::create([
            'aktivitas' => 'Menambah monitoring aplikasi: ' . $applink->name,
        ]);

        $data = [
            'nama_aplikasi' => $applink->name,
            'status'        => $request->status,
            'tanggal'       => $request->tanggal,
            'deskripsi'     => $request->deskripsi,
        ];

        if ($request->hasFile('file')) {
            $paths = [];
            foreach ($request->file('file') as $file) {
                $paths[] = $file->store('files', 'public');
            }
            $data['file'] = json_encode($paths);
        }

        Monitoring::create($data);

        return redirect()->route('monitoring.index')
            ->with('success', 'Data monitoring berhasil disimpan!');
    }

    public function show(string $id)
    {
        $monitoring = Monitoring::findOrFail($id);
        return view('monitoring.show', compact('monitoring'));
    }

    public function edit(string $id)
    {
        $monitoring = Monitoring::findOrFail($id);
        $applinks   = AppLink::orderBy('name')->get();

        return view('monitoring.edit', compact('monitoring', 'applinks'));
    }

    public function update(Request $request, string $id)
    {
        $monitoring = Monitoring::findOrFail($id);

        $request->validate([
            'applink_id' => 'required|exists:app_links,id',
            'status' => 'required|in:hijau,kuning,merah',
            'tanggal' => 'required|date',
            'file.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        $applink = AppLink::findOrFail($request->applink_id);

        MonitoringLog::create([
            'aktivitas' => 'Mengubah monitoring aplikasi: ' . $applink->name,
        ]);

        $data = [
            'nama_aplikasi' => $applink->name,
            'status'        => $request->status,
            'tanggal'       => $request->tanggal,
            'deskripsi'     => $request->deskripsi,
        ];

        if ($request->hasFile('file')) {
            if ($monitoring->file) {
                $oldFiles = json_decode($monitoring->file, true);
                if (is_array($oldFiles)) {
                    foreach ($oldFiles as $oldFile) {
                            Storage::disk('public')->delete($oldFile);
                    }
                }
            }

            $paths = [];
            foreach ($request->file('file') as $file) {
                $paths[] = $file->store('files', 'public');
            }
            $data['file'] = json_encode($paths);
        }

        $monitoring->update($data);

        return redirect()->route('monitoring.index')
            ->with('success', 'Data monitoring berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $monitoring = Monitoring::findOrFail($id);
        
        if ($monitoring->file) {
            $files = json_decode($monitoring->file, true);
            if (is_array($files)) {
                foreach ($files as $file) {
                        Storage::disk('public')->delete($file);
                }
            }
        }

        MonitoringLog::create([
            'aktivitas' => 'Menghapus monitoring aplikasi: ' . $monitoring->nama_aplikasi,
        ]);
        
        $monitoring->delete();

        return redirect()->route('monitoring.index')
            ->with('success', 'Data monitoring berhasil dihapus!');
    }

    public function download(string $id)
    {
        $monitoring = Monitoring::findOrFail($id);
        $pdf = Pdf::loadView('monitoring.pdf', compact('monitoring'));

        return $pdf->download('detail-monitoring-'.$monitoring->id.'.pdf');
    }

    public function userDashboard()
    {
        $monitorings = Monitoring::orderBy('created_at', 'desc')->paginate(10);
        return view('monitoring.user-index', compact('monitorings'));
    }

    public function dashboard()
    {
        $applinks = AppLink::orderBy('name')->get();
        $appChartData = [];

        // Gunakan periode 30 hari terakhir (konsisten dengan laporan)
        $start = now()->subDays(29)->startOfDay();
        $end = now()->endOfDay();

        foreach ($applinks as $app) {
            $dailyData = [];
            $labels = [];

            // Query sama seperti di laporan untuk consistency
            $rows = Monitoring::where('nama_aplikasi', $app->name)
                ->whereBetween(DB::raw('DATE(COALESCE(tanggal, created_at))'), [
                    $start->toDateString(),
                    $end->toDateString()
                ])
                ->selectRaw("DATE(COALESCE(tanggal, created_at)) as day, status, COUNT(*) as total")
                ->groupBy(DB::raw("DATE(COALESCE(tanggal, created_at))"), 'status')
                ->get();

            // Siapkan map untuk 30 hari dengan default 0
            $dayMap = [];
            for ($i = 0; $i < 30; $i++) {
                $dayKey = $start->copy()->addDays($i)->toDateString();
                $dayMap[$dayKey] = ['hijau' => 0, 'kuning' => 0, 'merah' => 0];
            }

            // Populate map dengan data dari query
            foreach ($rows as $r) {
                $dayKey = $r->day;
                if (!isset($dayMap[$dayKey])) {
                    continue;
                }
                if (in_array($r->status, ['hijau', 'kuning', 'merah'], true)) {
                    $dayMap[$dayKey][$r->status] = (int) $r->total;
                }
            }

            // Rakit data 30 hari berurutan
            for ($i = 0; $i < 30; $i++) {
                $dayKey = $start->copy()->addDays($i)->toDateString();
                $labels[] = (string)($i + 1);

                $dailyData[] = [
                    'date' => $dayKey,
                    'hijau' => $dayMap[$dayKey]['hijau'],
                    'kuning' => $dayMap[$dayKey]['kuning'],
                    'merah' => $dayMap[$dayKey]['merah'],
                    'total' => $dayMap[$dayKey]['hijau'] + $dayMap[$dayKey]['kuning'] + $dayMap[$dayKey]['merah'],
                ];
            }

            $appChartData[$app->id] = [
                'app' => $app,
                'labels' => $labels,
                'dailyData' => $dailyData,
            ];
        }

        return view('menu', compact('applinks', 'appChartData'));
    }

    public function userShow(string $id)
    {
        $monitoring = Monitoring::findOrFail($id);
        return view('monitoring.user-show', compact('monitoring'));
    }
}
