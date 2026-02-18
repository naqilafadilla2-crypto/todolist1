<?php

namespace App\Livewire;

use App\Models\Device;
use App\Models\DeviceLog;
use App\Models\Rack;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class RackManagement extends Component
{
    use WithFileUploads;

    public $racks = [];
    public $devices = [];
    public $selectedRack = null;
    public $showDeviceModal = false;
    public $editingDevice = null;
    
    // Form fields
    public $deviceName = '';
    public $deviceIp = '';
    public $deviceImage = null;
    public $deviceDescription = '';
    public $deviceHeightUnits = 1;
    public $deviceRackId = null;
    public $deviceRackUnit = null;
    public $showLogModal = false;
    public $selectedDeviceForLog = null;
    public $showReportModal = false;
    public $reportRackId = null;
    public $reportType = 'summary'; // summary, devices, status, logs
    
    // Rack modal properties
    public $showRackModal = false;
    public $editingRackId = null;
    public $rackName = '';
    public $rackTotalUnits = 42;


    public function mount()
    {
        $this->loadData();
        // lakukan pengecekan status awal berdasarkan IP
        $this->checkDeviceStatus();
    }

    public function loadData()
    {
        $this->racks = Rack::with('devices')->get();
        $this->devices = Device::whereNull('rack_id')->get();
    }

    public function selectRack($rackId)
    {
        $this->selectedRack = $rackId;
        $this->loadData();
    }

    public function openDeviceModal($deviceId = null, $rackId = null, $rackUnit = null)
    {
        $this->editingDevice = $deviceId;
        if ($deviceId) {
            $device = Device::find($deviceId);
            $this->deviceName = $device->name;
            $this->deviceIp = $device->ip_address;
            $this->deviceDescription = $device->description;
            $this->deviceHeightUnits = $device->height_units;
            $this->deviceRackId = $device->rack_id;
            $this->deviceRackUnit = $device->rack_unit;
        } else {
            $this->resetDeviceForm();
            // Pre-fill rack and unit if provided
            if ($rackId) {
                $this->deviceRackId = $rackId;
            }
            if ($rackUnit) {
                $this->deviceRackUnit = $rackUnit;
            }
        }
        $this->showDeviceModal = true;
    }

    public function closeDeviceModal()
    {
        $this->showDeviceModal = false;
        $this->resetDeviceForm();
        $this->editingDevice = null;
    }

    public function resetDeviceForm()
    {
        $this->deviceName = '';
        $this->deviceIp = '';
        $this->deviceImage = null;
        $this->deviceDescription = '';
        $this->deviceHeightUnits = 1;
        $this->deviceRackId = null;
        $this->deviceRackUnit = null;
    }

    public function updatedDeviceRackId()
    {
        // Reset rack unit when rack changes
        $this->deviceRackUnit = null;
    }

    public function getAvailableUnitsProperty()
    {
        if (!$this->deviceRackId) {
            return [];
        }

        $rack = Rack::find($this->deviceRackId);
        if (!$rack) {
            return [];
        }

        $occupiedUnits = Device::where('rack_id', $this->deviceRackId)
            ->where('id', '!=', $this->editingDevice ?? 0)
            ->get()
            ->flatMap(function($device) {
                $units = [];
                for ($i = 0; $i < $device->height_units; $i++) {
                    $units[] = $device->rack_unit + $i;
                }
                return $units;
            })
            ->toArray();

        $availableUnits = [];
        $requiredHeight = $this->deviceHeightUnits ?? 1;
        
        for ($i = 1; $i <= $rack->total_units; $i++) {
            // Check if this unit and required height units are available
            $isAvailable = true;
            
            for ($j = 0; $j < $requiredHeight; $j++) {
                if (in_array($i + $j, $occupiedUnits) || ($i + $j) > $rack->total_units) {
                    $isAvailable = false;
                    break;
                }
            }
            
            if ($isAvailable) {
                $availableUnits[] = $i;
            }
        }

        return $availableUnits;
    }

    public function saveDevice()
    {
        $this->validate([
            'deviceName' => 'required|string|max:255',
            'deviceIp' => 'nullable|ip',
            'deviceImage' => $this->editingDevice ? 'nullable|image|max:2048' : 'nullable|image|max:2048',
            'deviceHeightUnits' => 'required|integer|min:1|max:10',
            'deviceRackId' => 'nullable|exists:racks,id',
            'deviceRackUnit' => 'nullable|integer|min:1',
        ]);

        // Validate rack unit if rack is selected
        if ($this->deviceRackId && $this->deviceRackUnit) {
            $rack = Rack::find($this->deviceRackId);
            if ($rack) {
                // Check if unit is within rack limits
                if ($this->deviceRackUnit < 1 || ($this->deviceRackUnit + $this->deviceHeightUnits - 1) > $rack->total_units) {
                    $this->addError('deviceRackUnit', 'Unit yang dipilih melebihi batas rack.');
                    return;
                }

                // Check for conflicts with other devices
                $conflictingDevices = Device::where('rack_id', $this->deviceRackId)
                    ->where('id', '!=', $this->editingDevice ?? 0)
                    ->get()
                    ->filter(function($otherDevice) {
                        $otherStart = $otherDevice->rack_unit;
                        $otherEnd = $otherDevice->rack_unit + $otherDevice->height_units - 1;
                        $newStart = $this->deviceRackUnit;
                        $newEnd = $this->deviceRackUnit + $this->deviceHeightUnits - 1;
                        
                        // Check if ranges overlap
                        return ($newStart <= $otherEnd && $newEnd >= $otherStart);
                    });

                if ($conflictingDevices->count() > 0) {
                    $this->addError('deviceRackUnit', 'Unit yang dipilih sudah terisi oleh perangkat lain.');
                    return;
                }
            }
        }

        $data = [
            'name' => $this->deviceName,
            'ip_address' => $this->deviceIp,
            'description' => $this->deviceDescription,
            'height_units' => $this->deviceHeightUnits,
            'rack_id' => $this->deviceRackId,
            'rack_unit' => $this->deviceRackUnit,
            'status' => 'offline',
        ];

        if ($this->deviceImage) {
            if ($this->editingDevice) {
                $device = Device::find($this->editingDevice);
                if ($device->image) {
                    Storage::disk('public')->delete($device->image);
                }
            }
            $data['image'] = $this->deviceImage->store('devices', 'public');
        }

        if ($this->editingDevice) {
            Device::find($this->editingDevice)->update($data);
            $savedId = $this->editingDevice;
        } else {
            $device = Device::create($data);
            $savedId = $device->id;
        }

        // Periksa status perangkat yang baru disimpan/diupdate berdasarkan IP
        if (!empty($savedId)) {
            $this->checkDeviceStatus($savedId);
        } else {
            $this->loadData();
        }

        $this->closeDeviceModal();
        $this->dispatch('device-saved');
    }

    public function deleteDevice($deviceId)
    {
        $device = Device::find($deviceId);
        if ($device->image) {
            Storage::disk('public')->delete($device->image);
        }
        $device->delete();
        $this->loadData();
    }

    public function updateDevicePosition($deviceId, $rackId, $rackUnit)
    {
        $device = Device::find($deviceId);
        
        // Check if position is available
        if ($rackId && $rackUnit) {
            $rack = Rack::find($rackId);
            $conflictingDevices = Device::where('rack_id', $rackId)
                ->where('id', '!=', $deviceId)
                ->where(function($query) use ($rackUnit, $device) {
                    $query->whereBetween('rack_unit', [$rackUnit, $rackUnit + $device->height_units - 1])
                          ->orWhereBetween('rack_unit', [$rackUnit - $device->height_units + 1, $rackUnit]);
                })
                ->get();

            if ($conflictingDevices->count() > 0) {
                $this->dispatch('position-occupied');
                return;
            }
        }

        $device->update([
            'rack_id' => $rackId,
            'rack_unit' => $rackUnit,
        ]);

        $this->loadData();
    }

    public function checkDeviceStatus($deviceId = null)
    {
        if ($deviceId) {
            // Check single device
            $device = Device::find($deviceId);
            if ($device && $device->ip_address) {
                $previousStatus = $device->status;
                $status = $this->pingDevice($device->ip_address);
                $newStatus = $status ? 'online' : 'offline';
                
                $device->update([
                    'status' => $newStatus,
                    'last_checked_at' => now(),
                ]);

                // Log status change
                if ($previousStatus !== $newStatus) {
                    $message = $newStatus === 'online' 
                        ? "Perangkat kembali online" 
                        : "Perangkat mati/offline";
                    
                    DeviceLog::create([
                        'device_id' => $device->id,
                        'status' => $newStatus,
                        'previous_status' => $previousStatus,
                        'message' => $message,
                        'logged_at' => now(),
                    ]);
                }
            }
        } else {
            // Check all devices
            $devices = Device::whereNotNull('ip_address')->get();
            foreach ($devices as $device) {
                $previousStatus = $device->status;
                $status = $this->pingDevice($device->ip_address);
                $newStatus = $status ? 'online' : 'offline';
                
                $device->update([
                    'status' => $newStatus,
                    'last_checked_at' => now(),
                ]);

                // Log status change
                if ($previousStatus !== $newStatus) {
                    $message = $newStatus === 'online' 
                        ? "Perangkat kembali online" 
                        : "Perangkat mati/offline";
                    
                    DeviceLog::create([
                        'device_id' => $device->id,
                        'status' => $newStatus,
                        'previous_status' => $previousStatus,
                        'message' => $message,
                        'logged_at' => now(),
                    ]);
                }
            }
        }
        $this->loadData();
        $this->dispatch('status-updated');
    }

    private function pingDevice($ip)
    {
        // Windows ping command
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $ping = exec("ping -n 1 -w 1000 {$ip}", $output, $result);
            return $result === 0;
        }
        
        // Linux/Unix ping command
        $ping = exec("ping -c 1 -W 1 {$ip} 2>&1", $output, $result);
        return $result === 0;
    }

    public function showDeviceLogs($deviceId)
    {
        $this->selectedDeviceForLog = $deviceId;
        $this->showLogModal = true;
    }

    public function closeLogModal()
    {
        $this->showLogModal = false;
        $this->selectedDeviceForLog = null;
    }

    public function render()
    {
        $deviceLogs = [];
        if ($this->selectedDeviceForLog) {
            $deviceLogs = DeviceLog::where('device_id', $this->selectedDeviceForLog)
                ->orderBy('logged_at', 'desc')
                ->limit(100)
                ->get();
        }

        $reportData = [];
        if ($this->showReportModal) {
            $reportData = $this->generateReportData();
        }

        return view('livewire.rack-management', [
            'deviceLogs' => $deviceLogs,
            'reportData' => $reportData,
        ]);
    }

    public function openReportModal()
    {
        $this->showReportModal = true;
        $this->reportRackId = null;
        $this->reportType = 'summary';
    }

    public function closeReportModal()
    {
        $this->showReportModal = false;
        $this->reportRackId = null;
        $this->reportType = 'summary';
    }

    public function generateReportData()
    {
        if ($this->reportType === 'summary') {
            return $this->generateSummaryReport();
        } elseif ($this->reportType === 'devices') {
            return $this->generateDevicesReport();
        } elseif ($this->reportType === 'status') {
            return $this->generateStatusReport();
        } elseif ($this->reportType === 'logs') {
            return $this->generateLogsReport();
        }
        return [];
    }

    private function generateSummaryReport()
    {
        $racks = $this->reportRackId 
            ? Rack::where('id', $this->reportRackId)->get()
            : Rack::all();

        $summary = [];
        foreach ($racks as $rack) {
            $devices = Device::where('rack_id', $rack->id)->get();
            $onlineCount = $devices->where('status', 'online')->count();
            $offlineCount = $devices->where('status', 'offline')->count();
            $usedUnits = $devices->sum('height_units');

            $summary[] = [
                'rack_id' => $rack->id,
                'rack_name' => $rack->name,
                'total_units' => $rack->total_units,
                'used_units' => $usedUnits,
                'available_units' => $rack->total_units - $usedUnits,
                'utilization_percentage' => round(($usedUnits / $rack->total_units) * 100, 2),
                'total_devices' => $devices->count(),
                'online_devices' => $onlineCount,
                'offline_devices' => $offlineCount,
            ];
        }

        return [
            'type' => 'summary',
            'generated_at' => now(),
            'data' => $summary,
        ];
    }

    private function generateDevicesReport()
    {
        $query = Device::with('rack');
        
        if ($this->reportRackId) {
            $query->where('rack_id', $this->reportRackId);
        }

        $devices = $query->get()->map(function ($device) {
            return [
                'id' => $device->id,
                'name' => $device->name,
                'ip_address' => $device->ip_address ?? '-',
                'rack_name' => $device->rack ? $device->rack->name : 'Belum di Rack',
                'rack_unit' => $device->rack_unit ? "Unit {$device->rack_unit}" : '-',
                'height_units' => $device->height_units . 'U',
                'status' => ucfirst($device->status),
                'description' => $device->description ?? '-',
                'last_checked_at' => $device->last_checked_at ? $device->last_checked_at->format('d/m/Y H:i:s') : '-',
            ];
        });

        return [
            'type' => 'devices',
            'generated_at' => now(),
            'total_devices' => $devices->count(),
            'data' => $devices,
        ];
    }

    private function generateStatusReport()
    {
        $query = Device::query();
        
        if ($this->reportRackId) {
            $query->where('rack_id', $this->reportRackId);
        }

        $devices = $query->get();
        $onlineDevices = $devices->where('status', 'online')->values();
        $offlineDevices = $devices->where('status', 'offline')->values();

        return [
            'type' => 'status',
            'generated_at' => now(),
            'total_devices' => $devices->count(),
            'online_count' => $onlineDevices->count(),
            'offline_count' => $offlineDevices->count(),
            'online_percentage' => $devices->count() > 0 ? round(($onlineDevices->count() / $devices->count()) * 100, 2) : 0,
            'online_devices' => $onlineDevices->map(function ($device) {
                return [
                    'name' => $device->name,
                    'ip_address' => $device->ip_address ?? '-',
                    'rack_info' => $device->rack ? $device->rack->name . ' - Unit ' . $device->rack_unit : 'Belum di Rack',
                ];
            })->toArray(),
            'offline_devices' => $offlineDevices->map(function ($device) {
                return [
                    'name' => $device->name,
                    'ip_address' => $device->ip_address ?? '-',
                    'rack_info' => $device->rack ? $device->rack->name . ' - Unit ' . $device->rack_unit : 'Belum di Rack',
                ];
            })->toArray(),
        ];
    }

    private function generateLogsReport()
    {
        $query = DeviceLog::with('device');
        
        if ($this->reportRackId) {
            $query->whereHas('device', function ($q) {
                $q->where('rack_id', $this->reportRackId);
            });
        }

        $logs = $query->orderBy('logged_at', 'desc')->limit(500)->get()->map(function ($log) {
            return [
                'device_name' => $log->device->name,
                'ip_address' => $log->device->ip_address ?? '-',
                'status' => ucfirst($log->status),
                'previous_status' => $log->previous_status ? ucfirst($log->previous_status) : '-',
                'message' => $log->message ?? '-',
                'logged_at' => $log->logged_at->format('d/m/Y H:i:s'),
            ];
        });

        return [
            'type' => 'logs',
            'generated_at' => now(),
            'total_logs' => $logs->count(),
            'data' => $logs,
        ];
    }

    // Rack Management Methods
    public function openRackModal($rackId = null)
    {
        $this->editingRackId = $rackId;
        if ($rackId) {
            $rack = Rack::find($rackId);
            if ($rack) {
                $this->rackName = $rack->name;
                $this->rackTotalUnits = $rack->total_units;
            }
        } else {
            $this->resetRackForm();
        }
        $this->showRackModal = true;
    }

    public function closeRackModal()
    {
        $this->showRackModal = false;
        $this->resetRackForm();
        $this->editingRackId = null;
    }

    public function resetRackForm()
    {
        $this->rackName = '';
        $this->rackTotalUnits = 42;
    }

    public function saveRack()
    {
        $this->validate([
            'rackName' => 'required|string|max:255',
            'rackTotalUnits' => 'required|integer|min:1|max:100',
        ]);

        $data = [
            'name' => $this->rackName,
            'total_units' => $this->rackTotalUnits,
        ];

        if ($this->editingRackId) {
            Rack::find($this->editingRackId)->update($data);
        } else {
            Rack::create($data);
        }

        $this->closeRackModal();
        $this->loadData();
        $this->dispatch('rack-saved');
    }

    public function updateRackStatus()
    {
        $racks = Rack::with('devices')->get();
        
        foreach ($racks as $rack) {
            $totalDevices = $rack->devices()->count();
            
            if ($totalDevices === 0) {
                // Rack tanpa device dianggap offline
                $rack->update([
                    'status_online' => 'offline',
                    'last_checked_at' => now(),
                ]);
            } else {
                // Jika ada minimal 1 device online, rack dianggap online
                $onlineCount = $rack->devices()->where('status', 'online')->count();
                $rackStatus = $onlineCount > 0 ? 'online' : 'offline';
                
                $rack->update([
                    'status_online' => $rackStatus,
                    'last_checked_at' => now(),
                ]);
            }
        }
    }

    public function deleteRack($rackId)
    {
        $rack = Rack::find($rackId);
        
        if (!$rack) {
            return;
        }

        // Cek apakah ada device di rack ini
        $deviceCount = Device::where('rack_id', $rackId)->count();
        if ($deviceCount > 0) {
            $this->addError('rackDelete', "Tidak bisa menghapus rack karena masih ada {$deviceCount} perangkat di dalamnya. Pindahkan atau hapus semua perangkat terlebih dahulu.");
            return;
        }

        $rack->delete();
        $this->loadData();
        $this->dispatch('rack-deleted');
    }
}
