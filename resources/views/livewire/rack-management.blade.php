<div wire:poll.30s="checkDeviceStatus">
    <style>
        * {
            box-sizing: border-box;
        }

        .rack-management-header {
            background: linear-gradient(135deg, #2c2f7e 0%, #4a55d4 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 8px 24px rgba(44, 47, 126, 0.15);
        }

        .rack-management-header h2 {
            margin: 0 0 15px 0;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .header-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .header-actions button {
            padding: 12px 20px;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            position: relative;
            overflow: hidden;
        }

        .header-actions button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.2);
        }

        .header-actions button:active {
            transform: translateY(0);
        }

        .btn-add-rack {
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
        }

        .btn-report {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        }

        .btn-check-status {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        }

        .btn-add-device {
            background: linear-gradient(135deg, #2c2f7e 0%, #1a1d4d 100%);
        }

        .rack-container {
            display: flex;
            gap: 25px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .devices-pool {
            flex: 0 0 280px;
            border: none;
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .devices-pool h3 {
            margin: 0 0 20px 0;
            color: #2c2f7e;
            font-size: 16px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .devices-pool h3::before {
            content: '';
            width: 4px;
            height: 20px;
            background: linear-gradient(135deg, #2c2f7e, #4a55d4);
            border-radius: 2px;
        }

        .device-item {
            padding: 12px;
            margin-bottom: 12px;
            border: 2px solid #e8eaf6;
            border-radius: 8px;
            cursor: move;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s ease;
        }

        .device-item:hover {
            box-shadow: 0 6px 16px rgba(44, 47, 126, 0.12);
            border-color: #2c2f7e;
            transform: translateY(-2px);
        }

        .device-item img {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 6px;
            flex-shrink: 0;
            border: 2px solid #e8eaf6;
        }

        .device-item > div:nth-child(2) {
            flex: 1;
        }

        .device-item > div:nth-child(2) strong {
            color: #2c2f7e;
            font-weight: 700;
            font-size: 14px;
            display: block;
            margin-bottom: 4px;
        }

        .device-item > div:nth-child(2) small {
            color: #666;
            font-size: 12px;
        }

        .device-item > div:last-child {
            display: flex;
            gap: 4px;
            flex-shrink: 0;
        }

        .device-item button {
            font-size: 11px;
            padding: 5px 8px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-weight: 600;
            color: white;
        }

        .device-item button:hover {
            transform: scale(1.05);
        }

        .device-item button[wire\\:click*="openDeviceModal"] {
            background: #3498db;
        }

        .device-item button[wire\\:click*="deleteDevice"] {
            background: #e74c3c;
        }

        .device-item button[wire\\:click*="checkDeviceStatus"] {
            background: #2196F3;
        }

        .device-item button[wire\\:click*="showDeviceLogs"] {
            background: #9c27b0;
        }

        .status-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
            box-shadow: 0 0 4px rgba(0,0,0,0.2);
        }

        .status-online {
            background: #2ecc71;
        }

        .status-offline {
            background: #e74c3c;
        }

        .rack-visual {
            flex: 0 0 320px;
            border: none;
            background: white;
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .rack-visual:hover {
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        }

        .rack-unit {
            height: 22px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            padding: 0 8px;
            position: relative;
            cursor: pointer;
            background: #fafafa;
            transition: background 0.2s ease;
        }

        .rack-unit:hover {
            background: #f0f0f0;
        }

        .rack-unit-number {
            position: absolute;
            left: 8px;
            font-size: 10px;
            color: #999;
            font-weight: 600;
            min-width: 20px;
        }

        .device-slot {
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
            color: white;
            padding: 4px 8px;
            border-radius: 5px;
            margin-left: 30px;
            font-size: 11px;
            cursor: move;
            display: flex;
            align-items: flex-start;
            gap: 6px;
            min-height: 20px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            transition: all 0.2s ease;
        }

        .device-slot:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        .device-slot.offline {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        }

        .device-slot.kuning {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        }

        .device-slot img {
            width: 16px;
            height: 16px;
            border-radius: 2px;
            border: 1px solid rgba(255,255,255,0.3);
        }

        .rack-header {
            background: linear-gradient(135deg, #2c2f7e 0%, #4a55d4 100%);
            color: white;
            padding: 12px;
        }

        .rack-header-name {
            text-align: center;
            font-weight: 700;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .rack-header-actions {
            display: flex;
            gap: 6px;
            justify-content: center;
        }

        .rack-header-actions button {
            font-size: 12px;
            padding: 5px 12px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-weight: 600;
        }

        .rack-header-actions button:hover {
            transform: translateY(-2px);
        }

        .rack-header-actions button[wire\\:click*="openRackModal"] {
            background: #3498db;
            box-shadow: 0 3px 8px rgba(52, 152, 219, 0.3);
        }

        .rack-header-actions button[wire\\:click*="deleteRack"] {
            background: #e74c3c;
            box-shadow: 0 3px 8px rgba(231, 76, 60, 0.3);
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
            animation: fadeIn 0.2s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            padding: 25px;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .modal-content h3 {
            color: #2c2f7e;
            margin: 0 0 20px 0;
            font-size: 20px;
            font-weight: 700;
        }

        .modal-content label {
            display: block;
            color: #666;
            font-weight: 600;
            margin-bottom: 6px;
            font-size: 13px;
        }

        .modal-content input,
        .modal-content textarea,
        .modal-content select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 2px solid #e8eaf6;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s ease;
            font-family: inherit;
        }

        .modal-content input:focus,
        .modal-content textarea:focus,
        .modal-content select:focus {
            outline: none;
            border-color: #2c2f7e;
            box-shadow: 0 0 0 3px rgba(44, 47, 126, 0.1);
        }

        .modal-content textarea {
            resize: vertical;
            min-height: 100px;
        }

        .modal-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .modal-actions button {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .modal-actions button[type="submit"] {
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
            color: white;
        }

        .modal-actions button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(46, 204, 113, 0.3);
        }

        .modal-actions button:not([type="submit"]) {
            background: #ecf0f1;
            color: #666;
        }

        .modal-actions button:not([type="submit"]):hover {
            background: #e0e6e9;
        }

        @media (max-width: 768px) {
            .rack-container {
                flex-direction: column;
            }

            .devices-pool {
                flex: 1;
            }

            .rack-visual {
                flex: 1;
            }

            .header-actions {
                flex-direction: column;
            }

            .header-actions button {
                width: 100%;
            }
        }
    </style>

    <div style="padding: 30px 20px; background: #f5f7fa; min-height: 100vh;">
        <!-- mengupdate tampilan -->
        <div class="rack-management-header">
            <h2>Kelola Sistem Rack</h2>
            <div class="header-actions">
                <button wire:click="openRackModal()" class="btn-add-rack" title="Tambah rack baru">
                    Tambah Rack
                </button>
                <button wire:click="openReportModal()" class="btn-report" title="Buat laporan rack">
                    Report
                </button>
                <button wire:click="checkDeviceStatus()" class="btn-check-status" title="Cek status semua perangkat">
                    Cek Semua Status
                </button>
                <button wire:click="openDeviceModal()" class="btn-add-device">
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
                    <div style="margin-bottom: 12px;">
                        <button wire:click="openDeviceModal({{ $device->id }})" 
                                style="font-size: 11px; padding: 5px 8px; color: black;">Edit</button>
                        <button wire:click="deleteDevice({{ $device->id }})" 
                                style="font-size: 11px; padding: 5px 8px; color: red;">Hapus</button>
                        @if($device->ip_address)
                            <button wire:click="checkDeviceStatus({{ $device->id }})" 
                                    style="font-size: 11px; padding: 5px 8px; color: green;">Cek Status</button>
                        @endif
                        <button wire:click="showDeviceLogs({{ $device->id }})" 
                                style="font-size: 11px; padding: 5px 8px; color: black;">Riwayat</button>
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

                    <!-- Header rack: menampilkan nama rack dan tombol Edit / Hapus -->
                    <div class="rack-header">

                        <!-- Nama rack dan total unit -->
                        <div class="rack-header-name">
                            {{ $rack->name }} ({{ $rack->total_units }}U)
                        </div>

                      <!-- Tombol aksi rack (Edit & Hapus) -->
                        <div class="rack-header-actions">
                            <button wire:click="openRackModal({{ $rack->id }})" 
                                    title="Edit rack"
                                    style="color: black;">
                                Edit
                            </button>
                            <button wire:click="deleteRack({{ $rack->id }})" 
                                    onclick="return confirm('Yakin ingin menghapus rack ini? (Pastikan tidak ada device di dalamnya)')"
                                    title="Hapus rack"
                                    style="color: black;">
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
                <h3>{{ $editingDevice ? '✏️ Edit' : '➕ Tambah' }} Perangkat</h3>
                <form wire:submit.prevent="saveDevice">
                    <div>
                        <label>Nama Perangkat:</label>
                        <input type="text" wire:model="deviceName" placeholder="Contoh: Server 1">
                        @error('deviceName') <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label>IP Address:</label>
                        <input type="text" wire:model="deviceIp" placeholder="Contoh: 192.168.1.10">
                        @error('deviceIp') <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label>Gambar:</label>
                        <input type="file" wire:model="deviceImage" accept="image/*">
                        @if($editingDevice)
                            @php
                                $currentDevice = \App\Models\Device::find($editingDevice);
                            @endphp
                            @if($currentDevice && $currentDevice->image)
                                <img src="{{ asset('storage/' . $currentDevice->image) }}" style="width: 100px; margin-top: 10px; display: block; border-radius: 6px;">
                            @endif
                        @endif
                        @error('deviceImage') <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label>Deskripsi:</label>
                        <textarea wire:model="deviceDescription" placeholder="Masukkan deskripsi perangkat..."></textarea>
                    </div>
                    <div>
                        <label>Tinggi (U):</label>
                        <input type="number" wire:model.live="deviceHeightUnits" min="1" max="10">
                        @error('deviceHeightUnits') <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label>Pilih Rack:</label>
                        <select wire:model.live="deviceRackId">
                            <option value="">-- Pilih Rack (Opsional) --</option>
                            @foreach($racks as $rack)
                                <option value="{{ $rack->id }}">{{ $rack->name }} ({{ $rack->total_units }}U)</option>
                            @endforeach
                        </select>
                        <small style="color: #999; display: block; margin: 8px 0 0 0;">Kosongkan jika perangkat belum akan dipasang di rack</small>
                    </div>
                    @if($deviceRackId)
                        <div>
                            <label>Pilih Unit di Rack:</label>
                            <select wire:model="deviceRackUnit">
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
                                <small style="color: #e74c3c; display: block; margin: 8px 0 0 0;">Tidak ada unit tersedia untuk tinggi {{ $deviceHeightUnits }}U di rack ini</small>
                            @else
                                <small style="color: #999; display: block; margin: 8px 0 0 0;">Unit yang tersedia untuk tinggi {{ $deviceHeightUnits }}U</small>
                            @endif
                            @error('deviceRackUnit') <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span> @enderror
                        </div>
                    @endif
                    <div class="modal-actions">
                        <button type="button" wire:click="closeDeviceModal()">Batal</button>
                        <button type="submit">💾 Simpan</button>
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
            <div class="modal-content" style="max-width: 900px; max-height: 85vh; overflow-y: auto;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #e8eaf6;">
                    <h3 style="margin: 0; color: #2c2f7e;">📋 Riwayat Log - {{ $device->name ?? 'Perangkat' }}</h3>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <a href="{{ route('rack.devices.export-logs', $selectedDeviceForLog) }}" 
                           style="padding: 8px 15px; background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: 600; transition: all 0.2s ease;"
                           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(39, 174, 96, 0.3)'"
                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                            📊 Export Excel
                        </a>
                        <button wire:click="closeLogModal()" style="background: #f0f0f0; border: none; padding: 8px 15px; border-radius: 6px; cursor: pointer; font-weight: 600; color: #666; transition: all 0.2s ease;"
                                onmouseover="this.style.background='#e0e0e0'"
                                onmouseout="this.style.background='#f0f0f0'">✕ Tutup</button>
                    </div>
                </div>
                
                @if($logs->count() > 0)
                    <div style="margin-bottom: 20px; padding: 15px; background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%); border-left: 4px solid #2c2f7e; border-radius: 8px;">
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
                            <div>
                                <div style="font-size: 12px; color: #999; font-weight: 600; text-transform: uppercase;">Total Log</div>
                                <div style="font-size: 24px; font-weight: 700; color: #2c2f7e; margin-top: 5px;">{{ $logs->count() }}</div>
                            </div>
                            @if($device)
                                <div>
                                    <div style="font-size: 12px; color: #999; font-weight: 600; text-transform: uppercase;">Status Saat Ini</div>
                                    <div style="margin-top: 5px;">
                                        <span class="status-indicator status-{{ $device->status }}" style="display: inline-block; vertical-align: middle;"></span>
                                        <span style="font-size: 16px; font-weight: 700; color: #2c2f7e; vertical-align: middle;">{{ ucfirst($device->status) }}</span>
                                    </div>
                                </div>
                                @if($device->last_checked_at)
                                    <div>
                                        <div style="font-size: 12px; color: #999; font-weight: 600; text-transform: uppercase;">Terakhir Cek</div>
                                        <div style="font-size: 14px; color: #2c2f7e; margin-top: 5px; font-weight: 600;">{{ $device->last_checked_at->format('d/m/Y H:i:s') }}</div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                    
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #2c2f7e; color: white;">
                                <th style="padding: 12px; text-align: left; font-weight: 600;">Waktu</th>
                                <th style="padding: 12px; text-align: left; font-weight: 600;">Status</th>
                                <th style="padding: 12px; text-align: left; font-weight: 600;">Status Sebelumnya</th>
                                <th style="padding: 12px; text-align: left; font-weight: 600;">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                                <tr style="border-bottom: 1px solid #e8eaf6; transition: background-color 0.2s ease;"
                                    onmouseover="this.style.backgroundColor='#f8f9fa'"
                                    onmouseout="this.style.backgroundColor='transparent'">
                                    <td style="padding: 12px;">
                                        <strong style="color: #2c2f7e;">{{ $log->logged_at->format('d/m/Y') }}</strong><br>
                                        <small style="color: #999;">{{ $log->logged_at->format('H:i:s') }}</small>
                                    </td>
                                    <td style="padding: 12px;">
                                        <span class="status-indicator status-{{ $log->status }}"></span>
                                        <strong style="color: #2c2f7e;">{{ ucfirst($log->status) }}</strong>
                                    </td>
                                    <td style="padding: 12px;">
                                        @if($log->previous_status)
                                            <span class="status-indicator status-{{ $log->previous_status }}"></span>
                                            <span style="color: #666;">{{ ucfirst($log->previous_status) }}</span>
                                        @else
                                            <span style="color: #bbb;">-</span>
                                        @endif
                                    </td>
                                    <td style="padding: 12px; color: #666;">
                                        {{ $log->message ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div style="text-align: center; padding: 60px 20px; color: #999;">
                        <p style="font-size: 48px; margin: 0 0 15px 0;">📭</p>
                        <p style="font-size: 16px; margin: 0 0 10px 0; font-weight: 600;">Tidak ada riwayat log</p>
                        <p style="font-size: 14px; margin: 0;">Log akan muncul ketika status perangkat berubah (online/offline).</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Report Modal -->
    @if($showReportModal)
        <div class="modal show" wire:click.self="closeReportModal()">
            <div class="modal-content" style="max-width: 950px; max-height: 88vh; overflow-y: auto;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid #e8eaf6;">
                    <h3 style="margin: 0; color: #2c2f7e;">📊 Laporan Rack Management</h3>
                    <button wire:click="closeReportModal()" style="background: #f0f0f0; border: none; padding: 8px 15px; border-radius: 6px; cursor: pointer; font-weight: 600; color: #666; transition: all 0.2s ease;"
                            onmouseover="this.style.background='#e0e0e0'"
                            onmouseout="this.style.background='#f0f0f0'">✕ Tutup</button>
                </div>

                <!-- Filter Options -->
                <div style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%); padding: 18px; border-radius: 8px; margin-bottom: 25px; border: 1px solid #e8eaf6;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 700; color: #2c2f7e; font-size: 13px; text-transform: uppercase;">Tipe Laporan:</label>
                            <select wire:model.live="reportType" style="width: 100%; padding: 10px; border: 2px solid #e8eaf6; border-radius: 6px; color: #2c2f7e; font-weight: 600;">
                                <option value="summary">📋 Ringkasan Rack</option>
                                <option value="devices">🖥️ Daftar Perangkat</option>
                                <option value="status">✓ Status Perangkat</option>
                                <option value="logs">📝 Riwayat Log</option>
                            </select>
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 8px; font-weight: 700; color: #2c2f7e; font-size: 13px; text-transform: uppercase;">Filter Rack:</label>
                            <select wire:model.live="reportRackId" style="width: 100%; padding: 10px; border: 2px solid #e8eaf6; border-radius: 6px; color: #2c2f7e; font-weight: 600;">
                                <option value="">Semua Rack</option>
                                @foreach($racks as $rack)
                                    <option value="{{ $rack->id }}">{{ $rack->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div style="display: flex; align-items: flex-end;">
                            <button wire:click="$refresh" style="width: 100%; padding: 10px; background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; transition: all 0.2s ease;"
                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(52, 152, 219, 0.3)'"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                🔄 Refresh
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Report Content -->
                @if(!empty($reportData))
                    @if($reportData['type'] === 'summary')
                        <div style="margin-bottom: 20px;">
                            <h4 style="margin-bottom: 15px;"> Ringkasan Rack</h4>
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
                            <h4 style="margin-bottom: 15px;"> Daftar Perangkat (Total: {{ $reportData['total_devices'] }})</h4>
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
                            <h4 style="margin-bottom: 15px;"> Status Perangkat</h4>
                            
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
                                <h5 style="color: #28a745; margin-bottom: 10px;"> Perangkat Online ({{ count($reportData['online_devices']) }})</h5>
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
                                <h5 style="color: #dc3545; margin-bottom: 10px;"> Perangkat Offline ({{ count($reportData['offline_devices']) }})</h5>
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
                            <h4 style="margin-bottom: 15px;"> Riwayat Log (Total: {{ $reportData['total_logs'] }})</h4>
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
                <h3>{{ $editingRackId ? '✏️ Edit' : '➕ Tambah' }} Rack</h3>
                <form wire:submit.prevent="saveRack">
                    <div>
                        <label>Nama Rack:</label>
                        <input type="text" wire:model="rackName" placeholder="Contoh: Rack A, Server Rack 1">
                        @error('rackName') <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label>Total Unit (U):</label>
                        <input type="number" wire:model="rackTotalUnits" min="1" max="100">
                        @error('rackTotalUnits') <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span> @enderror
                    </div>
                    @if($editingRackId)
                        <div style="margin-bottom: 15px; padding: 12px; background: #cfe2ff; border-left: 4px solid #0d6efd; border-radius: 6px; font-size: 13px; color: #084298;">
                            <strong>ℹ️ Catatan:</strong> Mengubah total unit dapat mempengaruhi device yang sudah ditempatkan. Pastikan semua device masih dalam range yang valid.
                        </div>
                    @endif
                    <div class="modal-actions">
                        <button type="button" wire:click="closeRackModal()">Batal</button>
                        <button type="submit">💾 Simpan</button>
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
