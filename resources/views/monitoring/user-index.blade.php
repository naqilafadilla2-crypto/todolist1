@extends('layouts.sidebar')

@section('title', 'Dashboard Monitoring')

@section('content')
<style>
    .page-container {
        max-width: 1400px;
        margin: auto;
        padding: 20px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .page-header h2 {
        color: #2c2f7e;
        font-size: 24px;
        margin: 0;
    }

    .user-welcome {
        color: #2c2f7e;
        font-weight: 600;
        font-size: 14px;
    }

    .alert {
        padding: 12px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    table {
        width: 100%;
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    thead {
        background: #2c2f7e;
        color: white;
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
    }

    tbody tr {
        border-bottom: 1px solid #eee;
    }

    tbody tr:hover {
        background: #f8f9fa;
    }

    .status-hijau { background: #2ecc71; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; }
    .status-kuning { background: #f1c40f; color: #000; padding: 4px 12px; border-radius: 12px; font-size: 12px; }
    .status-merah { background: #e74c3c; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; }

    .btn-action {
        padding: 6px 12px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 13px;
        margin-right: 5px;
        display: inline-block;
    }

    .btn-view {
        background: #3498db;
        color: white;
    }

    .btn-view:hover {
        background: #2980b9;
    }

    .btn-download {
        background: #27ae60;
        color: white;
    }

    .btn-download:hover {
        background: #229954;
    }

    .no-data {
        text-align: center;
        padding: 40px;
        color: #999;
    }

    .thumb-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #2c2f7e;
        cursor: pointer;
        margin-right: 5px;
        margin-bottom: 5px;
    }

    .photo-container {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        max-width: 200px;
    }

    /* Widget Statistik */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        text-align: center;
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .stat-card h3 {
        margin: 0 0 10px 0;
        font-size: 14px;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
    }

    .stat-card .number {
        font-size: 32px;
        font-weight: bold;
        margin: 10px 0;
    }

    .stat-hijau .number { color: #2ecc71; }
    .stat-kuning .number { color: #f1c40f; }
    .stat-merah .number { color: #e74c3c; }
    .stat-total {
        background: linear-gradient(135deg, #2c2f7e 0%, #1f2258 100%);
        color: white;
    }

    .stat-total h3 { color: rgba(255,255,255,0.9); }
    .stat-total .number { color: white; }

    /* Tombol Laporan */
    .btn-laporan {
        background: #27ae60;
        color: white;
        padding: 12px 30px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        display: inline-block;
        transition: background 0.3s ease;
    }

    .btn-laporan:hover {
        background: #229954;
    }
</style>

@php
    $totalHijau = 0;
    $totalKuning = 0;
    $totalMerah = 0;
    $totalAll = $monitorings->count();

    foreach ($monitorings as $m) {
        if ($m->status === 'hijau') $totalHijau++;
        elseif ($m->status === 'kuning') $totalKuning++;
        elseif ($m->status === 'merah') $totalMerah++;
    }
@endphp

<div class="page-container">
    <div class="page-header">
        <h2>Dashboard Monitoring Aplikasi</h2>
        <div class="user-welcome">Halo, {{ auth()->user()->name }}</div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table id="monitoringTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Aplikasi</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Foto</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($monitorings as $monitoring)
            <tr>
                <td>{{ $monitoring->id }}</td>
                <td>{{ $monitoring->nama_aplikasi }}</td>
                <td>
                    <span class="status-{{ $monitoring->status }}">{{ ucfirst($monitoring->status) }}</span>
                </td>
                <td>{{ $monitoring->tanggal ? \Carbon\Carbon::parse($monitoring->tanggal)->format('d/m/Y') : '-' }}</td>
                <td>
                    @php
                        $files = $monitoring->file ? json_decode($monitoring->file, true) : [];
                        if (!is_array($files)) {
                            $files = $monitoring->file ? [$monitoring->file] : [];
                        }
                    @endphp

                    @if(count($files) > 0)
                        <div class="photo-container">
                            @foreach($files as $file)
                                @php
                                    $imgUrl = asset('storage/' . $file);
                                @endphp
                                <img src="{{ $imgUrl }}" alt="Foto" class="thumb-img" onclick="window.open('{{ $imgUrl }}', '_blank')"
                                     onerror="this.style.display='none';" title="Klik untuk melihat foto">
                            @endforeach
                        </div>
                    @else
                        <span style="color:#999;">-</span>
                    @endif
                </td>
                <td>{{ \Illuminate\Support\Str::limit($monitoring->deskripsi ?? '-', 50) }}</td>
                <td>
                    <a href="{{ route('monitoring.user.show', $monitoring->id) }}" class="btn-action btn-view">Detail</a>
                    <a href="{{ route('monitoring.download', $monitoring->id) }}" class="btn-action btn-download">Download PDF</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="no-data">Belum ada data monitoring</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
