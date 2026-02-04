@extends('layouts.sidebar')

@section('title', 'Dashboard')

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
                <h1>APLIKASI PEMANTAUAN PENGECEKAN LAYANAN BAKTI</h1>
                <p class="subtitle">Dashboard Monitoring & Status Aplikasi</p>
            </div>
            <div class="user-info">
                <div class="welcome">Selamat Datang, <strong>{{ auth()->user()->name }}</strong></div>
                <div class="date">{{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->format('d F Y, H:i') }} WIB</div>
            </div>
        </div>
    </div>

    @php
        use App\Models\Monitoring;
        
        // Statistik Overall (Semua Aplikasi)
        $allMonitorings = Monitoring::all();
        $totalAll = $allMonitorings->count();
        $hijauAll = $allMonitorings->where('status', 'hijau')->count();
        $kuningAll = $allMonitorings->where('status', 'kuning')->count();
        $merahAll = $allMonitorings->where('status', 'merah')->count();
        
        $persenHijauAll = $totalAll > 0 ? round(($hijauAll / $totalAll) * 100, 1) : 0;
        $persenKuningAll = $totalAll > 0 ? round(($kuningAll / $totalAll) * 100, 1) : 0;
        $persenMerahAll = $totalAll > 0 ? round(($merahAll / $totalAll) * 100, 1) : 0;
        
        // Hitung angle untuk pie chart
        $hijauAngle = $persenHijauAll * 3.6; // 360 / 100
        $kuningAngle = ($persenHijauAll + $persenKuningAll) * 3.6;
        
        // Statistik per aplikasi
        $applinks = \App\Models\AppLink::orderBy('name')->get();
        $appStats = [];
        foreach ($applinks as $app) {
            $appMonitorings = Monitoring::where('nama_aplikasi', $app->name)->get();
            $totalApp = $appMonitorings->count();
            $hijauApp = $appMonitorings->where('status', 'hijau')->count();
            $kuningApp = $appMonitorings->where('status', 'kuning')->count();
            $merahApp = $appMonitorings->where('status', 'merah')->count();
            
            $appStats[$app->id] = [
                'name' => $app->name,
                'total' => $totalApp,
                'hijau' => $hijauApp,
                'kuning' => $kuningApp,
                'merah' => $merahApp,
                'persen_hijau' => $totalApp > 0 ? round(($hijauApp / $totalApp) * 100, 1) : 0,
                'persen_kuning' => $totalApp > 0 ? round(($kuningApp / $totalApp) * 100, 1) : 0,
                'persen_merah' => $totalApp > 0 ? round(($merahApp / $totalApp) * 100, 1) : 0,
            ];
        }
    @endphp

    <!-- SUMMARY STATS -->
    <div class="summary-grid">
        <div class="summary-card primary">
            <div class="summary-card-header">
                <h4>Total Aplikasi</h4>
                <div class="summary-card-icon">🌐</div>
            </div>
            <h2>{{ \App\Models\AppLink::count() }}</h2>
            <div class="change">Aplikasi terdaftar</div>
        </div>

        <div class="summary-card success">
            <div class="summary-card-header">
                <h4>Status Hijau</h4>
                <div class="summary-card-icon">✅</div>
            </div>
            <h2>{{ $hijauAll }}</h2>
            <div class="change">{{ $persenHijauAll }}% dari total monitoring</div>
        </div>

        <div class="summary-card warning">
            <div class="summary-card-header">
                <h4>Status Kuning</h4>
                <div class="summary-card-icon">⚠️</div>
            </div>
            <h2>{{ $kuningAll }}</h2>
            <div class="change">{{ $persenKuningAll }}% dari total monitoring</div>
        </div>

        <div class="summary-card danger">
            <div class="summary-card-header">
                <h4>Status Merah</h4>
                <div class="summary-card-icon">🔴</div>
            </div>
            <h2>{{ $merahAll }}</h2>
            <div class="change">{{ $persenMerahAll }}% dari total monitoring</div>
        </div>
    </div>

    <!-- CHART SECTION -->
    <div class="chart-section">
        <h3 class="section-title">Status Monitoring Keseluruhan</h3>
        
        <div class="chart-container">
            <!-- Chart Overall -->
            <div class="chart-box">
                <h4>Semua Aplikasi</h4>
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
