<?php

namespace App\Http\Controllers;

use App\Exports\DeviceLogExport;
use App\Exports\RackReportExport;
use App\Models\Device;
use App\Models\DeviceLog;
use App\Models\Rack;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Maatwebsite\Excel\Facades\Excel;

class RackController extends Controller
{
    public function index()
    {
        return view('rack.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_units' => 'required|integer|min:1|max:100',
        ]);

        Rack::create($request->all());

        return redirect()->route('rack.index')->with('success', 'Rack berhasil ditambahkan');
    }

    public function destroy($id)
    {
        $rack = Rack::findOrFail($id);
        $rack->delete();

        return redirect()->route('rack.index')->with('success', 'Rack berhasil dihapus');
    }

    public function checkAllDevices()
    {
        Artisan::call('devices:check-status');
        
        return response()->json([
            'success' => true,
            'message' => 'Device status check completed',
            'output' => Artisan::output()
        ]);
    }

    public function checkDevice($deviceId)
    {
        Artisan::call('devices:check-status', ['--device-id' => $deviceId]);
        
        return response()->json([
            'success' => true,
            'message' => 'Device status check completed',
            'output' => Artisan::output()
        ]);
    }

    public function exportDeviceLogs($deviceId)
    {
        $device = Device::findOrFail($deviceId);
        
        $logs = DeviceLog::where('device_id', $deviceId)
            ->orderBy('logged_at', 'desc')
            ->get();

        $filename = 'riwayat-log-' . str_replace(' ', '-', strtolower($device->name)) . '-' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new DeviceLogExport($logs, $device->name), $filename);
    }

    public function report(Request $request)
    {
        $query = Rack::with('devices');

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $racks = $query->get();

        $totalRacks = $racks->count();
        $totalDevices = $racks->sum(function ($rack) {
            return $rack->devices->count();
        });
        $onlineDevices = $racks->sum(function ($rack) {
            return $rack->devices->where('status', 'online')->count();
        });
        $offlineDevices = $totalDevices - $onlineDevices;
        $onlineRacks = $racks->where('status_online', 'online')->count();
        $offlineRacks = $totalRacks - $onlineRacks;

        return view('rack.report', compact(
            'racks',
            'totalRacks',
            'totalDevices',
            'onlineDevices',
            'offlineDevices',
            'onlineRacks',
            'offlineRacks'
        ));
    }

    public function pdf(Request $request)
    {
        $query = Rack::with('devices');

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $racks = $query->get();

        $pdf = Pdf::loadView('rack.report-pdf', [
            'racks' => $racks,
        ]);

        return $pdf->download('laporan-rack-' . now()->format('YmdHis') . '.pdf');
    }

    public function excel(Request $request)
    {
        $query = Rack::with('devices');

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $racks = $query->get();

        return Excel::download(
            new RackReportExport($racks),
            'laporan-rack-' . now()->format('YmdHis') . '.xlsx'
        );
    }
}
