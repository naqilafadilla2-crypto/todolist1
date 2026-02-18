<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceChecklist;
use App\Models\MaintenanceLog;
use Illuminate\Http\Request;

class MaintenanceChecklistController extends Controller
{
    public function index()
    {
        $checklists = MaintenanceChecklist::with('maintenanceLogs')
        ->orderBy('perangkat')
        ->get();

    return view('maintenance.checklist', compact('checklists'));
    }

    public function update(Request $request, $id)
    {
        $checklist = MaintenanceChecklist::findOrFail($id);

        // Check if this is a perangkat name update (no quarter field means it's an edit)
        $isPerangkatUpdate = !$request->has('quarter') || empty($request->input('quarter'));
        
        if ($isPerangkatUpdate) {
            $request->validate([
                'perangkat' => 'required|string|max:100|unique:maintenance_checklists,perangkat,' . $id,
            ]);

            $checklist->update([
                'perangkat' => $request->perangkat,
            ]);

            return back()->with('success', "Nama perangkat berhasil diubah menjadi '{$request->perangkat}'.");
        }

        // Otherwise, process quarter status update
        $request->validate([
            'quarter' => 'required|in:q1,q2,q3,q4',
            'status' => 'required|in:belum,proses,selesai',
            'tanggal' => 'nullable|date',
        ]);

        $quarter = $request->quarter;
        $statusColumn = "status_{$quarter}";
        $tanggalColumn = "tanggal_{$quarter}";
        $checkedColumn = "checked_{$quarter}";

        $checklist->update([
            $statusColumn => $request->status,
            $tanggalColumn => $request->status === 'selesai' ? ($request->tanggal ?? now()->toDateString()) : null,
            $checkedColumn => $request->status === 'selesai' ? true : false,
        ]);

        return back()->with('success', "Status perangkat {$checklist->perangkat} Q" . strtoupper(str_replace('q', '', $quarter)) . " berhasil diupdate.");
    }

    public function toggleCheckbox(Request $request, $id)
    {
        $checklist = MaintenanceChecklist::findOrFail($id);

        $request->validate([
            'quarter' => 'required|in:q1,q2,q3,q4',
        ]);

        $statusColumn = "status_{$request->quarter}";
        $tanggalColumn = "tanggal_{$request->quarter}";
        $currentStatus = $checklist->{$statusColumn};

        // Cycle through statuses: belum → proses → selesai → belum
        $nextStatus = match($currentStatus) {
            'belum' => 'proses',
            'proses' => 'selesai',
            'selesai' => 'belum',
            default => 'belum'
        };

        // Update status and tanggal if selesai
        $updateData = [
            $statusColumn => $nextStatus,
        ];
        
        if ($nextStatus === 'selesai') {
            $updateData[$tanggalColumn] = now()->toDateString();
        } elseif ($nextStatus === 'belum') {
            $updateData[$tanggalColumn] = null;
        }

        $checklist->update($updateData);

        $statusNames = [
            'belum' => 'Belum (❌)',
            'proses' => 'Terjadwal (⏳)',
            'selesai' => 'Selesai (✓)'
        ];

        return back()->with('success', "Status Q{strtoupper(str_replace('q', '', $request->quarter))} berubah menjadi {$statusNames[$nextStatus]}.");
    }

    public function storePerangkat(Request $request)
    {
        $request->validate([
            'perangkat' => 'required|string|max:100|unique:maintenance_checklists,perangkat',
        ]);

        MaintenanceChecklist::create([
            'perangkat' => $request->perangkat,
            'status_q1' => 'belum',
            'status_q2' => 'belum',
            'status_q3' => 'belum',
            'status_q4' => 'belum',
            'checked_q1' => false,
            'checked_q2' => false,
            'checked_q3' => false,
            'checked_q4' => false,
        ]);

        return back()->with('success', "Perangkat '{$request->perangkat}' berhasil ditambahkan.");
    }

    public function deletePerangkat($id)
    {
        $checklist = MaintenanceChecklist::findOrFail($id);
        $perangkat = $checklist->perangkat;
        $checklist->delete();

        return back()->with('success', "Perangkat '{$perangkat}' berhasil dihapus.");
    }

    public function updateKeterangan(Request $request, $id)
    {
        $checklist = MaintenanceChecklist::findOrFail($id);

        $request->validate([
            'keterangan' => 'nullable|string',
        ]);

        $checklist->update([
            'keterangan' => $request->keterangan,
        ]);

        return back()->with('success', "Keterangan perangkat {$checklist->perangkat} berhasil diupdate.");
    }

    public function storeLog(Request $request, $id)
    {
        $checklist = MaintenanceChecklist::findOrFail($id);

        $request->validate([
            'tanggal' => 'required|date',
            'pic' => 'required|string|max:100',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'keterangan_kesimpulan' => 'nullable|string',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('maintenance-logs', 'public');
        }

        MaintenanceLog::create([
            'maintenance_checklist_id' => $id,
            'tanggal' => $request->tanggal,
            'pic' => $request->pic,
            'foto' => $fotoPath,
            'keterangan_kesimpulan' => $request->keterangan_kesimpulan,
        ]);

        return back()->with('success', "Log perawatan perangkat {$checklist->perangkat} berhasil ditambahkan.");
    }

    public function updateLog(Request $request, $id, $logId)
    {
        $checklist = MaintenanceChecklist::findOrFail($id);
        $log = MaintenanceLog::findOrFail($logId);

        $request->validate([
            'tanggal' => 'required|date',
            'pic' => 'required|string|max:100',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'keterangan_kesimpulan' => 'nullable|string',
        ]);

        $fotoPath = $log->foto;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('maintenance-logs', 'public');
        }

        $log->update([
            'tanggal' => $request->tanggal,
            'pic' => $request->pic,
            'foto' => $fotoPath,
            'keterangan_kesimpulan' => $request->keterangan_kesimpulan,
        ]);

        return back()->with('success', "Log perawatan perangkat {$checklist->perangkat} berhasil diupdate.");
    }

    public function deleteLog($id, $logId)
    {
        $log = MaintenanceLog::findOrFail($logId);
        $checklist = $log->maintenanceChecklist;
        $log->delete();

        return back()->with('success', "Log perawatan perangkat {$checklist->perangkat} berhasil dihapus.");
    }
}
