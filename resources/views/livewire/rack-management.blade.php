<div wire:poll.30s="checkDeviceStatus">
    <style>
        .rack-container {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }
        .rack-visual {
            width: 300px;
            border: 2px solid #333;
            background: #f5f5f5;
            position: relative;
        }
        .rack-unit {
            height: 20px;
            border-bottom: 1px solid #ddd;
            display: flex;
            align-items: center;
            padding: 0 5px;
            position: relative;
            cursor: pointer;
        }
        .rack-unit:hover {
            background: #e0e0e0;
        }
        .rack-unit-number {
            position: absolute;
            left: 5px;
            font-size: 10px;
            color: #666;
        }
        .device-slot {
            background: #4CAF50;
            color: white;
            padding: 2px 5px;
            border-radius: 3px;
            margin-left: 30px;
            font-size: 11px;
            cursor: move;
            display: flex;
            align-items: flex-start;
            gap: 5px;
            min-height: 18px;
        }
        .device-slot.offline {
            background: #f44336;
        }
        .device-slot img {
            width: 16px;
            height: 16px;
            border-radius: 2px;
        }
        .devices-pool {
            width: 250px;
            border: 1px solid #ddd;
            padding: 15px;
            background: white;
            border-radius: 8px;
        }
        .device-item {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            cursor: move;
            background: white;
        }
        .device-item:hover {
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .device-item img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 4px;
        }
        .status-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }
        .status-online {
            background: #4CAF50;
        }
        .status-offline {
            background: #f44336;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
        }
        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
        }
    </style>

    <div style="padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>Rack Management</h2>
            <div style="display: flex; gap: 10px;">
                <button wire:click="openRackModal()" 
                        style="padding: 10px 20px; background: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;"
                        title="Tambah rack baru">
                    Tambah Rack
                </button>
                <button wire:click="openReportModal()" 
                        style="padding: 10px 20px; background: #FF9800; color: white; border: none; border-radius: 5px; cursor: pointer;"
                        title="Buat laporan rack">
                    Report
                </button>
                <button wire:click="checkDeviceStatus()" 
                        style="padding: 10px 20px; background: #2196F3; color: white; border: none; border-radius: 5px; cursor: pointer;"
                        title="Cek status semua perangkat">
                    Cek Semua Status
                </button>
                <button wire:click="openDeviceModal()" style="padding: 10px 20px; background: #2c2f7e; color: white; border: none; border-radius: 5px; cursor: pointer;">
                    Tambah Perangkat
                </button>
            </div>
        </div>

        <div class="rack-container">
            <!-- Devices Pool -->
            <div class="devices-pool">
                <h3>Perangkat Tersedia</h3>
                @foreach($devices as $device)
                    <div class="device-item" 
                         draggable="true"
                         wire:key="device-{{ $device->id }}"
                         ondragstart="event.dataTransfer.setData('deviceId', {{ $device->id }})">
                        @if($device->image)
                            <img src="{{ asset('storage/' . $device->image) }}" alt="{{ $device->name }}">
                        @endif
                        <div>
                            <strong>{{ $device->name }}</strong><br>
                            <small>
                                <span class="status-indicator status-{{ $device->status }}"></span>
                                {{ $device->ip_address ?? 'No IP' }}
                            </small>
                        </div>
                        <div style="margin-top: 5px;">
                            <button wire:click="openDeviceModal({{ $device->id }})" style="font-size: 11px; padding: 3px 8px;">Edit</button>
                            <button wire:click="deleteDevice({{ $device->id }})" style="font-size: 11px; padding: 3px 8px; background: #f44336; color: white;">Hapus</button>
                            @if($device->ip_address)
                                <button wire:click="checkDeviceStatus({{ $device->id }})" style="font-size: 11px; padding: 3px 8px; background: #2196F3; color: white;">Cek Status</button>
                            @endif
                            <button wire:click="showDeviceLogs({{ $device->id }})" style="font-size: 11px; padding: 3px 8px; background: #9c27b0; color: white;">Riwayat</button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Racks -->
            @foreach($racks as $rack)
                <div class="rack-visual" 
                     ondrop="handleDrop(event, {{ $rack->id }})" 
                     ondragover="event.preventDefault()"
                     wire:key="rack-{{ $rack->id }}">
                    <div style="background: #333; color: white; padding: 10px; display: flex; justify-content: space-between; align-items: center;">
                        <div style="flex: 1; text-align: center; font-weight: bold;">
                            {{ $rack->name }} ({{ $rack->total_units }}U)
                        </div>
                        <div style="display: flex; gap: 5px;">
                            <button wire:click="openRackModal({{ $rack->id }})" 
                                    style="font-size: 12px; padding: 4px 10px; background: #2196F3; color: white; border: none; border-radius: 3px; cursor: pointer;"
                                    title="Edit rack">
                                Edit
                            </button>
                            <button wire:click="deleteRack({{ $rack->id }})" 
                                    onclick="return confirm('Yakin ingin menghapus rack ini? (Pastikan tidak ada device di dalamnya)')"
                                    style="font-size: 12px; padding: 4px 10px; background: #f44336; color: white; border: none; border-radius: 3px; cursor: pointer;"
                                    title="Hapus rack">
                                Hapus
                            </button>
                        </div>
                    </div>
                    @for($i = $rack->total_units; $i >= 1; $i--)
                        @php
                            $deviceInSlot = $rack->devices->firstWhere('rack_unit', $i);
                        @endphp
                        <div class="rack-unit" 
                             data-unit="{{ $i }}"
                             ondrop="handleDropUnit(event, {{ $rack->id }}, {{ $i }})"
                             ondragover="event.preventDefault()"
                             @if(!$deviceInSlot)
                             wire:click="openDeviceModal(null, {{ $rack->id }}, {{ $i }})"
                             style="cursor: pointer;"
                             title="Klik untuk tambah perangkat di unit {{ $i }}"
                             @else
                             style="cursor: move;"
                             title="Klik untuk edit atau drag untuk pindah"
                             @endif>
                            <span class="rack-unit-number">{{ $i }}</span>
                            @if($deviceInSlot)
                                <div class="device-slot {{ $deviceInSlot->status }}" 
                                     style="height: {{ $deviceInSlot->height_units * 20 - 2 }}px;"
                                     draggable="true"
                                     ondragstart="event.dataTransfer.setData('deviceId', {{ $deviceInSlot->id }})"
                                     title="{{ $deviceInSlot->name }} - {{ $deviceInSlot->ip_address ?? 'No IP' }} - Status: {{ $deviceInSlot->status }}">
                                    @if($deviceInSlot->image)
                                        <img src="{{ asset('storage/' . $deviceInSlot->image) }}" alt="{{ $deviceInSlot->name }}">
                                    @endif
                                    <div style="flex: 1; min-width: 0;">
                                        <div style="font-weight: bold; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $deviceInSlot->name }}</div>
                                        @if($deviceInSlot->ip_address)
                                            <div style="font-size: 9px; opacity: 0.9;">{{ $deviceInSlot->ip_address }}</div>
                                        @endif
                                        @if($deviceInSlot->last_checked_at)
                                            <div style="font-size: 8px; opacity: 0.7; margin-top: 2px;">
                                                {{ $deviceInSlot->last_checked_at->diffForHumans() }}
                                            </div>
                                        @endif
                                        <div style="margin-top: 3px;">
                                            <button wire:click="showDeviceLogs({{ $deviceInSlot->id }})" 
                                                    onclick="event.stopPropagation();"
                                                    style="font-size: 8px; padding: 2px 5px; background: #9c27b0; color: white; border: none; border-radius: 3px; cursor: pointer;">
                                                📋 Log
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endfor
                </div>
            @endforeach
        </div>
    </div>

    <!-- Device Modal -->
    @if($showDeviceModal)
        <div class="modal show" wire:click.self="closeDeviceModal()">
            <div class="modal-content">
                <h3>{{ $editingDevice ? 'Edit' : 'Tambah' }} Perangkat</h3>
                <form wire:submit.prevent="saveDevice">
                    <div style="margin-bottom: 15px;">
                        <label>Nama Perangkat:</label>
                        <input type="text" wire:model="deviceName" style="width: 100%; padding: 8px; margin-top: 5px;">
                        @error('deviceName') <span style="color: red;">{{ $message }}</span> @enderror
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label>IP Address:</label>
                        <input type="text" wire:model="deviceIp" style="width: 100%; padding: 8px; margin-top: 5px;">
                        @error('deviceIp') <span style="color: red;">{{ $message }}</span> @enderror
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label>Gambar:</label>
                        <input type="file" wire:model="deviceImage" accept="image/*" style="width: 100%; padding: 8px; margin-top: 5px;">
                        @if($editingDevice)
                            @php
                                $currentDevice = \App\Models\Device::find($editingDevice);
                            @endphp
                            @if($currentDevice && $currentDevice->image)
                                <img src="{{ asset('storage/' . $currentDevice->image) }}" style="width: 100px; margin-top: 10px; display: block;">
                            @endif
                        @endif
                        @error('deviceImage') <span style="color: red;">{{ $message }}</span> @enderror
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label>Deskripsi:</label>
                        <textarea wire:model="deviceDescription" style="width: 100%; padding: 8px; margin-top: 5px;"></textarea>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label>Tinggi (U):</label>
                        <input type="number" wire:model.live="deviceHeightUnits" min="1" max="10" style="width: 100%; padding: 8px; margin-top: 5px;">
                        @error('deviceHeightUnits') <span style="color: red;">{{ $message }}</span> @enderror
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label>Pilih Rack:</label>
                        <select wire:model.live="deviceRackId" style="width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ddd; border-radius: 4px;">
                            <option value="">-- Pilih Rack (Opsional) --</option>
                            @foreach($racks as $rack)
                                <option value="{{ $rack->id }}">{{ $rack->name }} ({{ $rack->total_units }}U)</option>
                            @endforeach
                        </select>
                        <small style="color: #666;">Kosongkan jika perangkat belum akan dipasang di rack</small>
                    </div>
                    @if($deviceRackId)
                        <div style="margin-bottom: 15px;">
                            <label>Pilih Unit di Rack:</label>
                            <select wire:model="deviceRackUnit" style="width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ddd; border-radius: 4px;">
                                <option value="">-- Pilih Unit --</option>
                                @foreach($this->availableUnits as $unit)
                                    <option value="{{ $unit }}">Unit {{ $unit }} 
                                        @if($deviceHeightUnits > 1)
                                            - {{ $unit + $deviceHeightUnits - 1 }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @if(count($this->availableUnits) == 0)
                                <small style="color: #f44336;">Tidak ada unit tersedia untuk tinggi {{ $deviceHeightUnits }}U di rack ini</small>
                            @else
                                <small style="color: #666;">Unit yang tersedia untuk tinggi {{ $deviceHeightUnits }}U</small>
                            @endif
                            @error('deviceRackUnit') <span style="color: red;">{{ $message }}</span> @enderror
                        </div>
                    @endif
                    <div style="display: flex; gap: 10px; justify-content: flex-end;">
                        <button type="button" wire:click="closeDeviceModal()" style="padding: 10px 20px; background: #ccc; border: none; border-radius: 5px; cursor: pointer;">Batal</button>
                        <button type="submit" style="padding: 10px 20px; background: #2c2f7e; color: white; border: none; border-radius: 5px; cursor: pointer;">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Log Modal -->
    @if($showLogModal && $selectedDeviceForLog)
        @php
            $device = \App\Models\Device::find($selectedDeviceForLog);
            $logs = \App\Models\DeviceLog::where('device_id', $selectedDeviceForLog)
                ->orderBy('logged_at', 'desc')
                ->limit(100)
                ->get();
        @endphp
        <div class="modal show" wire:click.self="closeLogModal()">
            <div class="modal-content" style="max-width: 800px; max-height: 80vh; overflow-y: auto;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3>Riwayat Log - {{ $device->name ?? 'Perangkat' }}</h3>
                    <div style="display: flex; gap: 10px;">
                        <a href="{{ route('rack.devices.export-logs', $selectedDeviceForLog) }}" 
                           style="padding: 8px 15px; background: #27ae60; color: white; text-decoration: none; border-radius: 5px; font-size: 14px;">
                            📊 Export Excel
                        </a>
                        <button wire:click="closeLogModal()" style="background: #ccc; border: none; padding: 5px 15px; border-radius: 5px; cursor: pointer;">✕</button>
                    </div>
                </div>
                
                @if($logs->count() > 0)
                    <div style="margin-bottom: 15px; padding: 10px; background: #f5f5f5; border-radius: 5px;">
                        <strong>Total Log:</strong> {{ $logs->count() }} entri
                        @if($device)
                            <br><strong>Status Saat Ini:</strong> 
                            <span class="status-indicator status-{{ $device->status }}"></span>
                            {{ ucfirst($device->status) }}
                            @if($device->last_checked_at)
                                <br><strong>Terakhir Dicek:</strong> {{ $device->last_checked_at->format('d/m/Y H:i:s') }}
                            @endif
                        @endif
                    </div>
                    
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #2c2f7e; color: white;">
                                <th style="padding: 10px; text-align: left;">Waktu</th>
                                <th style="padding: 10px; text-align: left;">Status</th>
                                <th style="padding: 10px; text-align: left;">Status Sebelumnya</th>
                                <th style="padding: 10px; text-align: left;">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                                <tr style="border-bottom: 1px solid #ddd;">
                                    <td style="padding: 10px;">
                                        <strong>{{ $log->logged_at->format('d/m/Y') }}</strong><br>
                                        <small style="color: #666;">{{ $log->logged_at->format('H:i:s') }}</small>
                                    </td>
                                    <td style="padding: 10px;">
                                        <span class="status-indicator status-{{ $log->status }}"></span>
                                        <strong>{{ ucfirst($log->status) }}</strong>
                                    </td>
                                    <td style="padding: 10px;">
                                        @if($log->previous_status)
                                            <span class="status-indicator status-{{ $log->previous_status }}"></span>
                                            {{ ucfirst($log->previous_status) }}
                                        @else
                                            <span style="color: #999;">-</span>
                                        @endif
                                    </td>
                                    <td style="padding: 10px;">
                                        {{ $log->message ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div style="text-align: center; padding: 40px; color: #999;">
                        <p>Tidak ada riwayat log untuk perangkat ini.</p>
                        <p style="font-size: 12px;">Log akan muncul ketika status perangkat berubah (online/offline).</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Report Modal -->
    @if($showReportModal)
        <div class="modal show" wire:click.self="closeReportModal()">
            <div class="modal-content" style="max-width: 900px; max-height: 85vh; overflow-y: auto;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3>Laporan Rack Management</h3>
                    <button wire:click="closeReportModal()" style="background: #ccc; border: none; padding: 5px 15px; border-radius: 5px; cursor: pointer;">✕</button>
                </div>

                <!-- Filter Options -->
                <div style="background: #f5f5f5; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                        <div>
                            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Tipe Laporan:</label>
                            <select wire:model.live="reportType" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                                <option value="summary">Ringkasan Rack</option>
                                <option value="devices">Daftar Perangkat</option>
                                <option value="status">Status Perangkat</option>
                                <option value="logs">Riwayat Log</option>
                            </select>
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 5px; font-weight: bold;">Filter Rack:</label>
                            <select wire:model.live="reportRackId" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                                <option value="">Semua Rack</option>
                                @foreach($racks as $rack)
                                    <option value="{{ $rack->id }}">{{ $rack->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div style="display: flex; align-items: flex-end;">
                            <button wire:click="$refresh" style="width: 100%; padding: 8px; background: #2196F3; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                🔄 Refresh
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Report Content -->
                @if(!empty($reportData))
                    @if($reportData['type'] === 'summary')
                        <div style="margin-bottom: 20px;">
                            <h4 style="margin-bottom: 15px;">📊 Ringkasan Rack</h4>
                            <div style="background: white; border-radius: 5px; overflow: hidden;">
                                <table style="width: 100%; border-collapse: collapse;">
                                    <thead>
                                        <tr style="background: #2c2f7e; color: white;">
                                            <th style="padding: 12px; text-align: left;">Rack</th>
                                            <th style="padding: 12px; text-align: center;">Total Unit</th>
                                            <th style="padding: 12px; text-align: center;">Unit Terpakai</th>
                                            <th style="padding: 12px; text-align: center;">Utilitas</th>
                                            <th style="padding: 12px; text-align: center;">Total Device</th>
                                            <th style="padding: 12px; text-align: center;">Online</th>
                                            <th style="padding: 12px; text-align: center;">Offline</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reportData['data'] as $rack)
                                            <tr style="border-bottom: 1px solid #ddd;">
                                                <td style="padding: 12px;"><strong>{{ $rack['rack_name'] }}</strong></td>
                                                <td style="padding: 12px; text-align: center;">{{ $rack['total_units'] }}</td>
                                                <td style="padding: 12px; text-align: center;">{{ $rack['used_units'] }}</td>
                                                <td style="padding: 12px; text-align: center;">
                                                    <div style="background: #f0f0f0; border-radius: 4px; padding: 5px;">
                                                        <div style="background: #4CAF50; width: {{ $rack['utilization_percentage'] }}%; height: 20px; border-radius: 3px; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: bold;">
                                                            {{ $rack['utilization_percentage'] }}%
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="padding: 12px; text-align: center;"><strong>{{ $rack['total_devices'] }}</strong></td>
                                                <td style="padding: 12px; text-align: center; color: #4CAF50;"><strong>{{ $rack['online_devices'] }}</strong></td>
                                                <td style="padding: 12px; text-align: center; color: #f44336;"><strong>{{ $rack['offline_devices'] }}</strong></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    @elseif($reportData['type'] === 'devices')
                        <div style="margin-bottom: 20px;">
                            <h4 style="margin-bottom: 15px;">📱 Daftar Perangkat (Total: {{ $reportData['total_devices'] }})</h4>
                            <div style="background: white; border-radius: 5px; overflow-x: auto;">
                                <table style="width: 100%; border-collapse: collapse;">
                                    <thead>
                                        <tr style="background: #2c2f7e; color: white;">
                                            <th style="padding: 10px; text-align: left;">Nama</th>
                                            <th style="padding: 10px; text-align: left;">IP Address</th>
                                            <th style="padding: 10px; text-align: left;">Rack</th>
                                            <th style="padding: 10px; text-align: left;">Unit</th>
                                            <th style="padding: 10px; text-align: left;">Ukuran</th>
                                            <th style="padding: 10px; text-align: left;">Status</th>
                                            <th style="padding: 10px; text-align: left;">Terakhir Cek</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($reportData['data'] as $device)
                                            <tr style="border-bottom: 1px solid #ddd;">
                                                <td style="padding: 10px;"><strong>{{ $device['name'] }}</strong></td>
                                                <td style="padding: 10px;"><code style="background: #f5f5f5; padding: 2px 5px; border-radius: 3px;">{{ $device['ip_address'] }}</code></td>
                                                <td style="padding: 10px;">{{ $device['rack_name'] }}</td>
                                                <td style="padding: 10px;">{{ $device['rack_unit'] }}</td>
                                                <td style="padding: 10px; text-align: center;">{{ $device['height_units'] }}</td>
                                                <td style="padding: 10px;">
                                                    <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; 
                                                    @if(strpos($device['status'], 'Online') !== false)
                                                        background: #d4edda; color: #155724;
                                                    @else
                                                        background: #f8d7da; color: #721c24;
                                                    @endif
                                                    ">
                                                        {{ $device['status'] }}
                                                    </span>
                                                </td>
                                                <td style="padding: 10px; font-size: 12px;">{{ $device['last_checked_at'] }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" style="padding: 20px; text-align: center; color: #999;">
                                                    Tidak ada perangkat
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    @elseif($reportData['type'] === 'status')
                        <div style="margin-bottom: 20px;">
                            <h4 style="margin-bottom: 15px;">🔋 Status Perangkat</h4>
                            
                            <!-- Status Summary Cards -->
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px;">
                                <div style="background: #d4edda; border-left: 4px solid #28a745; padding: 15px; border-radius: 5px;">
                                    <div style="font-size: 12px; color: #666;">ONLINE</div>
                                    <div style="font-size: 28px; font-weight: bold; color: #28a745;">{{ $reportData['online_count'] }}</div>
                                    <div style="font-size: 12px; color: #666; margin-top: 5px;">{{ $reportData['online_percentage'] }}%</div>
                                </div>
                                <div style="background: #f8d7da; border-left: 4px solid #dc3545; padding: 15px; border-radius: 5px;">
                                    <div style="font-size: 12px; color: #666;">OFFLINE</div>
                                    <div style="font-size: 28px; font-weight: bold; color: #dc3545;">{{ $reportData['offline_count'] }}</div>
                                    <div style="font-size: 12px; color: #666; margin-top: 5px;">{{ 100 - $reportData['online_percentage'] }}%</div>
                                </div>
                                <div style="background: #cfe2ff; border-left: 4px solid #0d6efd; padding: 15px; border-radius: 5px;">
                                    <div style="font-size: 12px; color: #666;">TOTAL</div>
                                    <div style="font-size: 28px; font-weight: bold; color: #0d6efd;">{{ $reportData['total_devices'] }}</div>
                                </div>
                            </div>

                            <!-- Online Devices -->
                            <div style="margin-bottom: 20px;">
                                <h5 style="color: #28a745; margin-bottom: 10px;">✅ Perangkat Online ({{ count($reportData['online_devices']) }})</h5>
                                <div style="background: white; border: 1px solid #ddd; border-radius: 5px; overflow: hidden;">
                                    @forelse($reportData['online_devices'] as $device)
                                        <div style="padding: 12px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between;">
                                            <div>
                                                <strong>{{ $device['name'] }}</strong><br>
                                                <small style="color: #666;">{{ $device['rack_info'] }} • {{ $device['ip_address'] }}</small>
                                            </div>
                                            <span style="background: #d4edda; color: #28a745; padding: 4px 12px; border-radius: 20px; height: fit-content; font-size: 12px;">Online</span>
                                        </div>
                                    @empty
                                        <div style="padding: 20px; text-align: center; color: #999;">
                                            Tidak ada perangkat online
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Offline Devices -->
                            <div style="margin-bottom: 20px;">
                                <h5 style="color: #dc3545; margin-bottom: 10px;">❌ Perangkat Offline ({{ count($reportData['offline_devices']) }})</h5>
                                <div style="background: white; border: 1px solid #ddd; border-radius: 5px; overflow: hidden;">
                                    @forelse($reportData['offline_devices'] as $device)
                                        <div style="padding: 12px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between;">
                                            <div>
                                                <strong>{{ $device['name'] }}</strong><br>
                                                <small style="color: #666;">{{ $device['rack_info'] }} • {{ $device['ip_address'] }}</small>
                                            </div>
                                            <span style="background: #f8d7da; color: #dc3545; padding: 4px 12px; border-radius: 20px; height: fit-content; font-size: 12px;">Offline</span>
                                        </div>
                                    @empty
                                        <div style="padding: 20px; text-align: center; color: #999;">
                                            Tidak ada perangkat offline
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                    @elseif($reportData['type'] === 'logs')
                        <div style="margin-bottom: 20px;">
                            <h4 style="margin-bottom: 15px;">📋 Riwayat Log (Total: {{ $reportData['total_logs'] }})</h4>
                            <div style="background: white; border-radius: 5px; overflow-x: auto;">
                                <table style="width: 100%; border-collapse: collapse;">
                                    <thead>
                                        <tr style="background: #2c2f7e; color: white;">
                                            <th style="padding: 10px; text-align: left;">Perangkat</th>
                                            <th style="padding: 10px; text-align: left;">IP Address</th>
                                            <th style="padding: 10px; text-align: center;">Status</th>
                                            <th style="padding: 10px; text-align: center;">Status Sebelumnya</th>
                                            <th style="padding: 10px; text-align: left;">Pesan</th>
                                            <th style="padding: 10px; text-align: left;">Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($reportData['data'] as $log)
                                            <tr style="border-bottom: 1px solid #ddd;">
                                                <td style="padding: 10px;"><strong>{{ $log['device_name'] }}</strong></td>
                                                <td style="padding: 10px;"><code style="background: #f5f5f5; padding: 2px 5px; border-radius: 3px;">{{ $log['ip_address'] }}</code></td>
                                                <td style="padding: 10px; text-align: center;">
                                                    <span style="display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;
                                                    @if(strpos($log['status'], 'Online') !== false)
                                                        background: #d4edda; color: #155724;
                                                    @else
                                                        background: #f8d7da; color: #721c24;
                                                    @endif
                                                    ">
                                                        {{ $log['status'] }}
                                                    </span>
                                                </td>
                                                <td style="padding: 10px; text-align: center; font-size: 12px;">{{ $log['previous_status'] }}</td>
                                                <td style="padding: 10px; font-size: 12px;">{{ $log['message'] }}</td>
                                                <td style="padding: 10px; font-size: 12px;">{{ $log['logged_at'] }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" style="padding: 20px; text-align: center; color: #999;">
                                                    Tidak ada riwayat log
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- Report Footer -->
                    <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #ddd; display: flex; justify-content: space-between; align-items: center;">
                        <div style="font-size: 12px; color: #666;">
                            Laporan dihasilkan pada: <strong>{{ $reportData['generated_at']->format('d/m/Y H:i:s') }}</strong>
                        </div>
                    </div>
                @else
                    <div style="text-align: center; padding: 40px; color: #999;">
                        <p>Pilih tipe laporan untuk melihat data.</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Rack Modal -->
    @if($showRackModal)
        <div class="modal show" wire:click.self="closeRackModal()">
            <div class="modal-content">
                <h3>{{ $editingRackId ? 'Edit' : 'Tambah' }} Rack</h3>
                <form wire:submit.prevent="saveRack">
                    <div style="margin-bottom: 15px;">
                        <label>Nama Rack:</label>
                        <input type="text" wire:model="rackName" style="width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ddd; border-radius: 4px;" placeholder="Contoh: Rack A, Server Rack 1, etc">
                        @error('rackName') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label>Total Unit (U):</label>
                        <input type="number" wire:model="rackTotalUnits" min="1" max="100" style="width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ddd; border-radius: 4px;">
                        @error('rackTotalUnits') <span style="color: red; font-size: 12px;">{{ $message }}</span> @enderror
                    </div>
                    @if($editingRackId)
                        <div style="margin-bottom: 15px; padding: 10px; background: #f0f0f0; border-radius: 4px; font-size: 12px; color: #666;">
                            <strong>Catatan:</strong> Mengubah total unit dapat mempengaruhi device yang sudah ditempatkan. Pastikan semua device masih dalam range yang valid.
                        </div>
                    @endif
                    <div style="display: flex; gap: 10px; justify-content: flex-end;">
                        <button type="button" wire:click="closeRackModal()" style="padding: 10px 20px; background: #ccc; border: none; border-radius: 5px; cursor: pointer;">Batal</button>
                        <button type="submit" style="padding: 10px 20px; background: #2c2f7e; color: white; border: none; border-radius: 5px; cursor: pointer;">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <script>
        function handleDrop(event, rackId) {
            event.preventDefault();
            const deviceId = event.dataTransfer.getData('deviceId');
            if (deviceId) {
                @this.call('updateDevicePosition', deviceId, rackId, null);
            }
        }

        function handleDropUnit(event, rackId, unit) {
            event.preventDefault();
            const deviceId = event.dataTransfer.getData('deviceId');
            if (deviceId) {
                @this.call('updateDevicePosition', deviceId, rackId, unit);
            }
        }

    </script>
</div>
