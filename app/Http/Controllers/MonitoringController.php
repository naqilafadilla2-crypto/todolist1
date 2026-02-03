<?php

namespace App\Http\Controllers;

use App\Models\AppLink;
use App\Models\Monitoring;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

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

    return view('monitoring.index', compact('monitorings'));
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

    public function userShow(string $id)
    {
        $monitoring = Monitoring::findOrFail($id);
        return view('monitoring.user-show', compact('monitoring'));
    }
}
