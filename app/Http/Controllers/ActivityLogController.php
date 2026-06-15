<?php

namespace App\Http\Controllers;

use App\Models\MonitoringLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display activity log listing
     */
    public function index()
    {
        $logs = MonitoringLog::orderBy('created_at', 'desc')->paginate(20);
        
        return view('activity-log.index', compact('logs'));
    }

    /**
     * Delete activity log
     */
    public function destroy($id)
    {
        $log = MonitoringLog::findOrFail($id);
        $log->delete();

        return redirect()->route('activity-log.index')->with('success', 'Log aktivitas berhasil dihapus.');
    }

    /**
     * Clear all activity logs
     */
    public function clearAll()
    {
        MonitoringLog::truncate();

        return redirect()->route('activity-log.index')->with('success', 'Semua log aktivitas berhasil dihapus.');
    }
}
