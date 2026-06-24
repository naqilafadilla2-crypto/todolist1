<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Rack</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
        }
        .header {
            margin-bottom: 20px;
            border-bottom: 2px solid #0a978e;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #0a978e;
            font-size: 22px;
        }
        .summary {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 10px;
        }
        .summary-card {
            background: #f8fafc;
            border-radius: 10px;
            padding: 12px 14px;
            flex: 1 1 200px;
        }
        .summary-card strong {
            display: block;
            font-size: 18px;
            margin-bottom: 6px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #d1d5db;
            padding: 10px;
            text-align: left;
        }
        th {
            background: #0a978e;
            color: white;
        }
        .status-online {
            background: #27ae60;
            color: white;
            padding: 4px 8px;
            border-radius: 999px;
            display: inline-block;
        }
        .status-offline {
            background: #e74c3c;
            color: white;
            padding: 4px 8px;
            border-radius: 999px;
            display: inline-block;
        }
        .progress {
            width: 100%;
            background: #e2f3f0;
            border-radius: 10px;
            overflow: hidden;
            height: 14px;
        }
        .progress-fill {
            background: #0a978e;
            height: 14px;
            text-align: right;
            padding-right: 6px;
            color: #fff;
            font-size: 10px;
            line-height: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Rack</h1>
        <p>Generated: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="summary">
        <div class="summary-card">
            <strong>Total Rack</strong>
            <span>{{ $racks->count() }}</span>
        </div>
        <div class="summary-card">
            <strong>Total Perangkat</strong>
            <span>{{ $racks->sum(fn($rack) => $rack->devices->count()) }}</span>
        </div>
        <div class="summary-card">
            <strong>Online</strong>
            <span>{{ $racks->sum(fn($rack) => $rack->devices->where('status', 'online')->count()) }}</span>
        </div>
        <div class="summary-card">
            <strong>Offline</strong>
            <span>{{ $racks->sum(fn($rack) => $rack->devices->where('status', 'offline')->count()) }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Rack</th>
                <th>Total Unit</th>
                <th>Unit Terpakai</th>
                <th>Utilisasi</th>
                <th>Perangkat</th>
                <th>Online</th>
                <th>Offline</th>
                <th>Status Rack</th>
            </tr>
        </thead>
        <tbody>
            @foreach($racks as $rack)
                @php
                    $usedUnits = $rack->devices->sum('height_units');
                    $utilization = $rack->total_units > 0 ? round(($usedUnits / $rack->total_units) * 100, 2) : 0;
                    $onlineDevices = $rack->devices->where('status', 'online')->count();
                    $offlineDevices = $rack->devices->where('status', 'offline')->count();
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $rack->name }}</td>
                    <td>{{ $rack->total_units }} U</td>
                    <td>{{ $usedUnits }} U</td>
                    <td>
                        <div class="progress">
                            <div class="progress-fill" style="width: {{ min($utilization, 100) }}%">{{ $utilization }}%</div>
                        </div>
                    </td>
                    <td>{{ $rack->devices->count() }}</td>
                    <td>{{ $onlineDevices }}</td>
                    <td>{{ $offlineDevices }}</td>
                    <td>
                        <span class="status-{{ $rack->status_online ?? 'offline' }}">
                            {{ ucfirst($rack->status_online ?? 'offline') }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
