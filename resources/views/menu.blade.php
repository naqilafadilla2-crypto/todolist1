@extends('layouts.sidebar')

@section('title', 'Monitoring')

@section('content')

<style>
    * {
        box-sizing: border-box;
    }

    body {
        background: #f5f7fa;
    }

    .dashboard-container {
        max-width: 1400px;
        margin: auto;
        padding: 30px 20px;
    }

    /* HEADER */
    .dashboard-header {
        background: linear-gradient(135deg, #2c2f7e 0%, #4a55d4 100%);
        color: white;
        padding: 30px;
        border-radius: 16px;
        margin-bottom: 30px;
        box-shadow: 0 8px 24px rgba(44, 47, 126, 0.15);
    }

    .dashboard-header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .dashboard-header h1 {
        font-size: 28px;
        margin: 0 0 8px 0;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .dashboard-header .subtitle {
        font-size: 14px;
        opacity: 0.9;
        margin: 0;
    }

    .user-info {
        text-align: right;
    }

    .user-info .welcome {
        font-size: 14px;
        opacity: 0.9;
        margin-bottom: 5px;
    }

    .user-info .date {
        font-size: 12px;
        opacity: 0.8;
    }

    /* SUMMARY STATS */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .summary-card {
        background: white;
        padding: 24px;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        border-left: 4px solid;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .summary-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }

    .summary-card.primary { border-left-color: #2c2f7e; }
    .summary-card.success { border-left-color: #2ecc71; }
    .summary-card.warning { border-left-color: #f1c40f; }
    .summary-card.danger { border-left-color: #e74c3c; }

    .summary-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .summary-card h4 {
        font-size: 13px;
        font-weight: 600;
        color: #666;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .summary-card-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .summary-card.primary .summary-card-icon { background: #e8eaf6; color: #2c2f7e; }
    .summary-card.success .summary-card-icon { background: #e8f5e9; color: #2ecc71; }
    .summary-card.warning .summary-card-icon { background: #fff9e6; color: #f1c40f; }
    .summary-card.danger .summary-card-icon { background: #ffebee; color: #e74c3c; }

    .summary-card h2 {
        font-size: 36px;
        margin: 0;
        font-weight: 700;
        color: #2c2f7e;
    }

    .summary-card .change {
        font-size: 12px;
        margin-top: 8px;
        color: #999;
    }

    /* MENU SECTION */
    .menu-section {
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .menu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }

    .menu-item {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 16px;
        padding: 24px 20px;
        text-align: center;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border: 1px solid #e8eaf6;
        position: relative;
        overflow: hidden;
    }

    .menu-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #2c2f7e, #4a55d4);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .menu-item:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 32px rgba(44,47,126,0.15);
        border-color: #2c2f7e;
    }

    .menu-item:hover::before {
        transform: scaleX(1);
    }

    .menu-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #f2f4ff;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: auto;
        margin-bottom: 15px;
    }

    .menu-icon img {
        width: 45px;
        height: 45px;
        object-fit: contain;
    }

    .menu-item h5 {
        font-size: 16px;
        color: #2c2f7e;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .menu-stats {
        margin-bottom: 15px;
        font-size: 12px;
    }

    .menu-stat-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 6px;
        padding: 6px 10px;
        border-radius: 8px;
        background: #f8f9fa;
        transition: background 0.2s ease;
    }

    .menu-stat-item:hover {
        background: #e8eaf6;
    }

    .menu-stat-label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        color: #666;
    }

    .menu-stat-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }

    .menu-stat-dot.hijau { background: #2ecc71; }
    .menu-stat-dot.kuning { background: #f1c40f; }
    .menu-stat-dot.merah { background: #e74c3c; }

    .menu-stat-percent {
        font-weight: 600;
        font-size: 11px;
        color: #2c2f7e;
    }

    .menu-no-data {
        font-size: 11px;
        color: #999;
        font-style: italic;
        margin-bottom: 15px;
    }

    .btn-visit {
        background: linear-gradient(135deg, #2c2f7e 0%, #4a55d4 100%);
        color: white;
        padding: 12px 24px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(44, 47, 126, 0.2);
        width: 100%;
        text-align: center;
    }

    .btn-visit:hover {
        background: linear-gradient(135deg, #1f2258 0%, #2c2f7e 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(44, 47, 126, 0.3);
    }

    /* CHART SECTION */
    .chart-section {
        background: white;
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .section-title {
        font-size: 20px;
        color: #2c2f7e;
        margin: 0 0 25px 0;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title::before {
        content: '';
        width: 4px;
        height: 24px;
        background: linear-gradient(135deg, #2c2f7e, #4a55d4);
        border-radius: 2px;
    }

    .chart-container {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .chart-box {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 16px;
        padding: 30px;
        border: 1px solid #e8eaf6;
        min-width: 300px;
    }

    .chart-box h4 {
        font-size: 16px;
        color: #2c2f7e;
        margin-bottom: 20px;
        font-weight: 600;
        text-align: center;
    }

    /* PIE CHART */
    .pie-chart {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        margin: 0 auto 15px;
        position: relative;
        background: conic-gradient(
            #2ecc71 0deg var(--hijau-angle, 0deg),
            #f1c40f var(--hijau-angle, 0deg) var(--kuning-angle, 0deg),
            #e74c3c var(--kuning-angle, 0deg) 360deg
        );
    }

    .pie-chart::before {
        content: '';
        position: absolute;
        width: 100px;
        height: 100px;
        background: white;
        border-radius: 50%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .chart-legend {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 15px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
    }

    .legend-color {
        width: 16px;
        height: 16px;
        border-radius: 4px;
    }

    .legend-hijau { background: #2ecc71; }
    .legend-kuning { background: #f1c40f; }
    .legend-merah { background: #e74c3c; }

    .legend-percent {
        font-weight: 600;
        color: #2c2f7e;
        margin-left: auto;
    }

    /* PROGRESS BAR */
    .progress-bar-container {
        margin-bottom: 15px;
    }

    .progress-label {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
        font-size: 12px;
        color: #666;
    }

    .progress-bar {
        height: 25px;
        border-radius: 12px;
        overflow: hidden;
        background: #e0e0e0;
        position: relative;
    }

    .progress-fill {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 11px;
        font-weight: 600;
        transition: width 0.5s ease;
    }

    /* RESPONSIVE */
    @media (max-width: 1200px) {
        .menu-grid {
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .dashboard-container {
            padding: 20px 15px;
        }

        .dashboard-header {
            padding: 20px;
        }

        .dashboard-header h1 {
            font-size: 22px;
        }

        .dashboard-header-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .user-info {
            text-align: left;
        }

        .summary-grid {
            grid-template-columns: 1fr;
        }

        .chart-section,
        .menu-section {
            padding: 20px;
        }

        .menu-grid {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
        }
    }
</style>

<div class="dashboard-container">

    <!-- HEADER -->
    <div class="dashboard-header">
        <div class="dashboard-header-content">
            <div>
                <h1>Aplikasi Pemantauan Pengecekan Layanan Bakti</h1>
                <p class="subtitle">Status Aplikasi Realtime</p>
            </div>
            <div class="user-info">
                <div class="welcome">Selamat Datang, <strong>{{ auth()->user()->name }}</strong></div>
                <div class="date">{{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->format('d F Y, H:i') }} WIB</div>
            </div>
        </div>
    </div>

    @php
use App\Models\Monitoring;
use App\Models\AppLink;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| SET WAKTU
|--------------------------------------------------------------------------
*/
$now   = Carbon::now('Asia/Jakarta');
$today = $now->toDateString();
$year  = $now->year;

/*
|--------------------------------------------------------------------------
| STATISTIK PER HARI (HARI INI)
|--------------------------------------------------------------------------
*/
$dailyStats = Monitoring::select('status', DB::raw('COUNT(*) as total'))
    ->whereDate('created_at', $today)
    ->groupBy('status')
    ->pluck('total', 'status');

$hijauHariIni  = $dailyStats['hijau'] ?? 0;
$kuningHariIni = $dailyStats['kuning'] ?? 0;
$merahHariIni  = $dailyStats['merah'] ?? 0;
$totalHariIni  = $dailyStats->sum();

/*
|--------------------------------------------------------------------------
| STATISTIK PER TAHUN (TAHUN INI)
|--------------------------------------------------------------------------
*/
$yearlyStats = Monitoring::select('status', DB::raw('COUNT(*) as total'))
    ->whereYear('created_at', $year)
    ->groupBy('status')
    ->pluck('total', 'status');

$hijauTahunIni  = $yearlyStats['hijau'] ?? 0;
$kuningTahunIni = $yearlyStats['kuning'] ?? 0;
$merahTahunIni  = $yearlyStats['merah'] ?? 0;
$totalTahunIni  = $yearlyStats->sum();

/*
|--------------------------------------------------------------------------
| STATISTIK KESELURUHAN (ALL TIME)
|--------------------------------------------------------------------------
*/
$allStats = Monitoring::select('status', DB::raw('COUNT(*) as total'))
    ->groupBy('status')
    ->pluck('total', 'status');

$hijauAll  = $allStats['hijau'] ?? 0;
$kuningAll = $allStats['kuning'] ?? 0;
$merahAll  = $allStats['merah'] ?? 0;
$totalAll  = $allStats->sum();

$persenHijauAll  = $totalAll > 0 ? round(($hijauAll / $totalAll) * 100, 1) : 0;
$persenKuningAll = $totalAll > 0 ? round(($kuningAll / $totalAll) * 100, 1) : 0;
$persenMerahAll  = $totalAll > 0 ? round(($merahAll / $totalAll) * 100, 1) : 0;

/*
|--------------------------------------------------------------------------
| ANGLE PIE CHART
|--------------------------------------------------------------------------
*/
$hijauAngle  = $persenHijauAll * 3.6;
$kuningAngle = ($persenHijauAll + $persenKuningAll) * 3.6;

/*
|--------------------------------------------------------------------------
| STATISTIK PER APLIKASI
|--------------------------------------------------------------------------
*/
$applinks = AppLink::orderBy('name')->get();
$appStats = [];

foreach ($applinks as $app) {

    $stats = Monitoring::select('status', DB::raw('COUNT(*) as total'))
        ->where('nama_aplikasi', $app->name)
        ->groupBy('status')
        ->pluck('total', 'status');

    $totalApp  = $stats->sum();
    $hijauApp  = $stats['hijau'] ?? 0;
    $kuningApp = $stats['kuning'] ?? 0;
    $merahApp  = $stats['merah'] ?? 0;

    $appStats[$app->id] = [
        'name' => $app->name,
        'total' => $totalApp,
        'hijau' => $hijauApp,
        'kuning' => $kuningApp,
        'merah' => $merahApp,
        'persen_hijau'  => $totalApp > 0 ? round(($hijauApp / $totalApp) * 100, 1) : 0,
        'persen_kuning' => $totalApp > 0 ? round(($kuningApp / $totalApp) * 100, 1) : 0,
        'persen_merah'  => $totalApp > 0 ? round(($merahApp / $totalApp) * 100, 1) : 0,
    ];
}
@endphp

   <!-- SUMMARY STATS - HARI INI -->
<h3 style="margin: 30px 0 15px;"> Status Hari Ini</h3>

<div class="summary-card primary" style="padding: 30px;">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 25px;">

        <!-- TOTAL -->
        <div>
            <div class="summary-card-header">
                <h4>Total Monitoring</h4>
            </div>
            <h2>{{ $totalHariIni }}</h2>
            <div class="change">Data monitoring hari ini</div>
        </div>

        <!-- HIJAU -->
        <div>
            <div class="summary-card-header">
                <h4>Status Hijau</h4>
            </div>
            <h2>{{ $hijauHariIni }}</h2>
            <div class="change">Aplikasi normal hari ini</div>
        </div>

        <!-- KUNING -->
        <div>
            <div class="summary-card-header">
                <h4>Status Kuning</h4>
            </div>
            <h2>{{ $kuningHariIni }}</h2>
            <div class="change">Perlu perhatian hari ini</div>
        </div>

        <!-- MERAH -->
        <div>
            <div class="summary-card-header">
                <h4>Status Merah</h4>
            </div>
            <h2>{{ $merahHariIni }}</h2>
            <div class="change">Down / error hari ini</div>
        </div>

    </div>
</div>


<!-- SUMMARY STATS - TAHUN INI -->
<h3 style="margin: 40px 0 10px;">Status Tahun {{ date('Y') }}</h3>

<div class="summary-grid">
    <div class="summary-card primary">
        <div class="summary-card-header">
            <h4>Total Monitoring</h4>
            <div class="summary-card-icon"></div>
        </div>
        <h2>{{ $totalTahunIni }}</h2>
        <div class="change">Data monitoring tahun ini</div>
    </div>

    <div class="summary-card success">
        <div class="summary-card-header">
            <h4>Status Hijau</h4>
            <div class="summary-card-icon"></div>
        </div>
        <h2>{{ $hijauTahunIni }}</h2>
        <div class="change">Normal sepanjang tahun</div>
    </div>

    <div class="summary-card warning">
        <div class="summary-card-header">
            <h4>Status Kuning</h4>
            <div class="summary-card-icon"></div>
        </div>
        <h2>{{ $kuningTahunIni }}</h2>
        <div class="change">Perlu perhatian tahun ini</div>
    </div>

    <div class="summary-card danger">
        <div class="summary-card-header">
            <h4>Status Merah</h4>
            <div class="summary-card-icon"></div>
        </div>
        <h2>{{ $merahTahunIni }}</h2>
        <div class="change">Gangguan tahun ini</div>
    </div>
</div>

    <!-- CHART SECTION - MONITORING & RACK STATUS -->
    @php
        use App\Models\Rack;
        
        // Rack data
        $racks = Rack::with('devices')->get();
        $totalRacks = $racks->count();
        $onlineRacks = $racks->filter(function($rack) { return $rack->status_online === 'online'; })->count();
        $offlineRacks = $totalRacks - $onlineRacks;
        
        // Rack pie chart angles
        $persenOnlineRack = $totalRacks > 0 ? round(($onlineRacks / $totalRacks) * 100, 1) : 0;
        $persenOfflineRack = $totalRacks > 0 ? round(($offlineRacks / $totalRacks) * 100, 1) : 0;
        $onlineRackAngle = $persenOnlineRack * 3.6;
    @endphp
    
    <div class="chart-section">
        <h3 class="section-title">Dashboard Monitoring & Rack Management</h3>
        
        <div class="chart-container" style="display: flex; gap: 40px; justify-content: center; flex-wrap: wrap;">
            <!-- Chart Monitoring Overall -->
            <div class="chart-box">
                <h4>Status Aplikasi</h4>
                <div class="pie-chart" style="--hijau-angle: {{ $hijauAngle }}deg; --kuning-angle: {{ $kuningAngle }}deg;"></div>
                <div class="chart-legend">
                    <div class="legend-item">
                        <span class="legend-color legend-hijau"></span>
                        <span>Hijau</span>
                        <span class="legend-percent">{{ $persenHijauAll }}% ({{ $hijauAll }})</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-color legend-kuning"></span>
                        <span>Kuning</span>
                        <span class="legend-percent">{{ $persenKuningAll }}% ({{ $kuningAll }})</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-color legend-merah"></span>
                        <span>Merah</span>
                        <span class="legend-percent">{{ $persenMerahAll }}% ({{ $merahAll }})</span>
                    </div>
                </div>
            </div>

            <!-- Chart Rack Status -->
            <div class="chart-box">
                <h4>Status Rack</h4>
                @if($totalRacks > 0)
                    <div class="pie-chart" style="--hijau-angle: {{ $onlineRackAngle }}deg; --kuning-angle: {{ $onlineRackAngle }}deg; background: conic-gradient(
                        #2ecc71 0deg {{ $onlineRackAngle }}deg,
                        #e74c3c {{ $onlineRackAngle }}deg 360deg
                    );"></div>
                    <div class="chart-legend">
                        <div class="legend-item">
                            <span class="legend-color legend-hijau"></span>
                            <span>Online</span>
                            <span class="legend-percent">{{ $persenOnlineRack }}% ({{ $onlineRacks }})</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color legend-merah"></span>
                            <span>Offline</span>
                            <span class="legend-percent">{{ $persenOfflineRack }}% ({{ $offlineRacks }})</span>
                        </div>
                    </div>
                @else
                    <div style="text-align: center; padding: 30px; color: #999;">
                        <p style="font-size: 14px;">Belum ada rack</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- RACK MANAGEMENT STATUS - SUMMARY & LIST -->
    @php
        $racks = Rack::with('devices')->get();
        $totalRacks = $racks->count();
        $onlineRacks = $racks->filter(function($rack) { return $rack->status_online === 'online'; })->count();
        $offlineRacks = $totalRacks - $onlineRacks;
    @endphp

    <div class="chart-section" style="margin-top: 30px;">
        <h3 class="section-title">Detail Rack Management</h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <!-- RACK SUMMARY CARDS -->
            <div class="summary-card primary">
                <div class="summary-card-header">
                    <h4>Total Rack</h4>
                    <div class="summary-card-icon">🗄️</div>
                </div>
                <h2>{{ $totalRacks }}</h2>
                <div class="change">Jumlah rack keseluruhan</div>
            </div>

            <div class="summary-card success">
                <div class="summary-card-header">
                    <h4>Rack Online</h4>
                    <div class="summary-card-icon">✅</div>
                </div>
                <h2>{{ $onlineRacks }}</h2>
                <div class="change">Rack yang aktif</div>
            </div>

            <div class="summary-card danger">
                <div class="summary-card-header">
                    <h4>Rack Offline</h4>
                    <div class="summary-card-icon">❌</div>
                </div>
                <h2>{{ $offlineRacks }}</h2>
                <div class="change">Rack yang tidak aktif</div>
            </div>
        </div>

        <!-- RACK LIST -->
        <div style="margin-top: 20px;">
            <h4 style="color: #2c2f7e; margin-bottom: 15px; font-weight: 600;">Daftar Rack Detail</h4>
            
            @if($totalRacks > 0)
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 15px;">
                    @foreach($racks as $rack)
                        @php
                            $totalDevices = $rack->getTotalDevicesCount();
                            $onlineDevices = $rack->getOnlineDevicesCount();
                            $offlineDevices = $rack->getOfflineDevicesCount();
                            $statusColor = $rack->status_online === 'online' ? '#2ecc71' : '#e74c3c';
                            $statusText = $rack->status_online === 'online' ? 'Online' : 'Offline';
                        @endphp
                        <div style="background: white; border: 2px solid {{ $statusColor }}; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                                <h5 style="margin: 0; color: #2c2f7e; font-weight: 600;">{{ $rack->name }}</h5>
                                <span style="background: {{ $statusColor }}; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                    {{ $statusText }}
                                </span>
                            </div>
                            
                            @if($rack->description)
                                <p style="font-size: 13px; color: #666; margin: 0 0 12px 0;">{{ $rack->description }}</p>
                            @endif
                            
                            <div style="background: #f8f9fa; padding: 12px; border-radius: 8px; margin-bottom: 12px;">
                                <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 6px;">
                                    <span style="color: #666;">Total Devices:</span>
                                    <strong style="color: #2c2f7e;">{{ $totalDevices }} / {{ $rack->total_units }}U</strong>
                                </div>
                                <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 6px;">
                                    <span style="color: #666;">Online:</span>
                                    <strong style="color: #2ecc71;">{{ $onlineDevices }}</strong>
                                </div>
                                <div style="display: flex; justify-content: space-between; font-size: 13px;">
                                    <span style="color: #666;">Offline:</span>
                                    <strong style="color: #e74c3c;">{{ $offlineDevices }}</strong>
                                </div>
                            </div>
                            
                            @if($rack->last_checked_at)
                                <div style="font-size: 11px; color: #999; text-align: right;">
                                    Pengecekan: {{ $rack->last_checked_at->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB
                                </div>
                            @endif
                            
                            <a href="{{ route('rack.index') }}" style="display: inline-block; margin-top: 10px; padding: 8px 16px; background: linear-gradient(135deg, #2c2f7e 0%, #4a55d4 100%); color: white; text-decoration: none; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                Detail Rack
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 40px; background: #f8f9fa; border-radius: 12px; color: #999;">
                    <div style="font-size: 48px; margin-bottom: 15px;">🗄️</div>
                    <p style="font-size: 16px; margin: 0;">
                        Belum ada rack. Buat rack baru di menu <b>Rack Management</b>.
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- MENU APLIKASI -->
    <div class="menu-section">
        <h3 class="section-title">Daftar Aplikasi</h3>
    <div class="menu-grid">
        @forelse($applinks as $app)
            @php
                $image = $app->image ? asset('storage/'.$app->image) : asset('images/bakti.png');
                $stat = $appStats[$app->id] ?? null;
            @endphp
            <div class="menu-item">
                <div class="menu-icon">
                    <img src="{{ $image }}" alt="{{ $app->name }}">
                </div>
                <h5>{{ $app->name }}</h5>
                
                @if($stat && $stat['total'] > 0)
                    <div class="menu-stats">
                        <div class="menu-stat-item">
                            <span class="menu-stat-label">
                                <span class="menu-stat-dot hijau"></span>
                                <span>Hijau</span>
                            </span>
                            <span class="menu-stat-percent">{{ $stat['persen_hijau'] }}%</span>
                        </div>
                        <div class="menu-stat-item">
                            <span class="menu-stat-label">
                                <span class="menu-stat-dot kuning"></span>
                                <span>Kuning</span>
                            </span>
                            <span class="menu-stat-percent">{{ $stat['persen_kuning'] }}%</span>
                        </div>
                        <div class="menu-stat-item">
                            <span class="menu-stat-label">
                                <span class="menu-stat-dot merah"></span>
                                <span>Merah</span>
                            </span>
                            <span class="menu-stat-percent">{{ $stat['persen_merah'] }}%</span>
                        </div>
                    </div>
                @else
                    <div class="menu-no-data">Belum ada data monitoring</div>
                @endif
                
                <a href="{{ $app->url }}" target="_blank" class="btn-visit">
                    Kunjungi Website
                </a>
            </div>
        @empty
            <div style="grid-column:1 / -1; text-align:center; padding:40px; color:#999;">
                <div style="font-size:48px; margin-bottom:15px;">📱</div>
                <p style="font-size:16px; margin:0;">
                Belum ada aplikasi. Tambah dari menu <b>Kelola Aplikasi</b>.
            </p>
            </div>
        @endforelse
        </div>
    </div>

</div>

@endsection
