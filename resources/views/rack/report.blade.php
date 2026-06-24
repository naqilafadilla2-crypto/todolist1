@extends('layouts.sidebar')

@section('title', 'Laporan Rack')

@section('content')
<style>
    .report-container {
        max-width: 1200px;
        margin: auto;
        padding: 25px 20px;
    }

    .report-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .report-card {
        background: white;
        border-radius: 16px;
        padding: 22px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        min-width: 210px;
        flex: 1;
    }

    .report-card h3 {
        margin: 0 0 12px 0;
        color: #0a978e;
        font-size: 16px;
        font-weight: 700;
    }

    .report-card p {
        margin: 0;
        font-size: 32px;
        font-weight: 700;
        color: #1f2937;
    }

    .report-card small {
        display: block;
        margin-top: 8px;
        color: #6b7280;
    }

    .report-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: center;
    }

    .report-actions form,
    .report-actions a {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .report-actions button,
    .report-actions a {
        border: none;
        border-radius: 10px;
        padding: 12px 18px;
        background: #0a978e;
        color: #fff;
        text-decoration: none;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .report-actions button:hover,
    .report-actions a:hover {
        background: #08786f;
    }

    .report-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }

    .report-table th,
    .report-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #ecf0f3;
        font-size: 14px;
    }

    .report-table th {
        background: #0a978e;
        color: #ffffff;
        text-align: left;
    }

    .report-table tbody tr:hover {
        background: #f8fbff;
    }

    .status-chip {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 999px;
        color: #fff;
        font-weight: 700;
        font-size: 12px;
    }

    .status-online { background: #27ae60; }
    .status-offline { background: #e74c3c; }

    .progress-bar {
        background: #e5f6f0;
        border-radius: 999px;
        overflow: hidden;
        height: 18px;
    }

    .progress-fill {
        background: #0a978e;
        height: 100%;
        text-align: right;
        color: white;
        padding-right: 8px;
        font-size: 12px;
        line-height: 18px;
        font-weight: 700;
    }
</style>

<div class="report-container">
    <div class="report-header">
        <div>
            <h1 style="margin:0; color:#0a978e;">Laporan Rack</h1>
            <p style="margin: 8px 0 0 0; color:#4b5563;">Ringkasan detail kapasitas dan status rack.</p>
        </div>

        <div class="report-actions">
            <form method="GET" action="{{ route('rack.report') }}">
                <input type="text" name="name" placeholder="Cari nama rack..." value="{{ request('name') }}" style="padding: 10px 12px; border-radius: 10px; border: 1px solid #d1d5db;">
                <button type="submit">Filter</button>
            </form>
            <a href="{{ route('rack.report.pdf', request()->only('name')) }}">Download PDF</a>
            <a href="{{ route('rack.report.excel', request()->only('name')) }}">Download Excel</a>
        </div>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:20px; margin-bottom: 20px;">
        <div class="report-card">
            <h3>Total Rack</h3>
            <p>{{ $totalRacks }}</p>
            <small>Jumlah rack yang tercatat</small>
        </div>
        <div class="report-card">
            <h3>Total Perangkat</h3>
            <p>{{ $totalDevices }}</p>
            <small>Perangkat terpasang pada rack</small>
        </div>
        <div class="report-card">
            <h3>Online</h3>
            <p>{{ $onlineDevices }}</p>
            <small>Perangkat aktif</small>
        </div>
        <div class="report-card">
            <h3>Offline</h3>
            <p>{{ $offlineDevices }}</p>
            <small>Perangkat tidak aktif</small>
        </div>
    </div>

    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 60px;">No</th>
                <th>Rack</th>
                <th style="text-align:center;">Total Unit</th>
                <th style="text-align:center;">Unit Terpakai</th>
                <th style="text-align:center;">Utilisasi</th>
                <th style="text-align:center;">Perangkat</th>
                <th style="text-align:center;">Online</th>
                <th style="text-align:center;">Offline</th>
                <th style="text-align:center;">Status Rack</th>
            </tr>
        </thead>
        <tbody>
            @forelse($racks as $rack)
                @php
                    $usedUnits = $rack->devices->sum('height_units');
                    $utilization = $rack->total_units > 0 ? round(($usedUnits / $rack->total_units) * 100, 2) : 0;
                    $onlineDevices = $rack->devices->where('status', 'online')->count();
                    $offlineDevices = $rack->devices->where('status', 'offline')->count();
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $rack->name }}</td>
                    <td style="text-align:center;">{{ $rack->total_units }}U</td>
                    <td style="text-align:center;">{{ $usedUnits }}U</td>
                    <td style="text-align:center;">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ min($utilization, 100) }}%;">{{ $utilization }}%</div>
                        </div>
                    </td>
                    <td style="text-align:center;">{{ $rack->devices->count() }}</td>
                    <td style="text-align:center;">{{ $onlineDevices }}</td>
                    <td style="text-align:center;">{{ $offlineDevices }}</td>
                    <td style="text-align:center;"><span class="status-chip status-{{ $rack->status_online ?? 'offline' }}">{{ ucfirst($rack->status_online ?? 'offline') }}</span></td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align:center; padding: 20px; color: #6b7280;">Belum ada data rack.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
