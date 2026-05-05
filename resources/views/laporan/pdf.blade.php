<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Monitoring</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #333;
        }

        h3 {
            margin: 0 0 6px 0;
            color:#2c3e8f;
        }

        .summary {
            margin-bottom: 10px;
            width: 100%;
        }

        .summary td {
            padding: 6px;
            font-weight: bold;
            color: #fff;
            text-align: center;
            border-radius: 6px;
        }

        .summary .hijau { background:#28a745; }
        .summary .kuning { background:#f1c40f; color:#000; }
        .summary .merah { background:#dc3545; }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px 6px;
            border: 1px solid #dfe2ec;
        }

        th {
            background: #f4f6fb;
            color: #2c3e8f;
        }

        .badge {
            padding: 3px 6px;
            border-radius: 5px;
            color:#fff;
            font-size:10px;
            text-transform:uppercase;
        }

        .hijau { background:#28a745; }
        .kuning { background:#f1c40f; color:#000; }
        .merah { background:#dc3545; }
        /* ===== CHART (seperti contoh) ===== */
.laporan-chart-wrap {
    margin: 10px 0 24px;
}
.laporan-chart-box {
    display: flex;
    gap: 16px;
    align-items: flex-start;
    flex-wrap: wrap;
}
.laporan-chart-canvas {
    flex: 1;
    min-width: 320px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
    padding: 20px 24px;
    height: 320px;
}
.laporan-chart-legend {
    width: 220px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    font-size: 13px;
    font-weight: 600;
}
.laporan-legend-row {
    display: flex;
    align-items: center;
    gap: 10px;
}
.laporan-legend-swatch {
    width: 56px;
    height: 22px;
    border-radius: 3px;
}
.laporan-legend-hijau { background: #2ecc71; }
.laporan-legend-kuning { background: #f1c40f; }
.laporan-legend-merah { background: #e74c3c; }
.laporan-chart-title {
    margin-top: 10px;
    text-align: center;
    font-size: 16px;
    font-weight: 700;
    color: #2c2f7e;
}
    </style>
</head>
<body>

@php
    // Gunakan total dari controller (hitung dari grafik data)
    $totalHijau = $totalHijau ?? 0;
    $totalKuning = $totalKuning ?? 0;
    $totalMerah = $totalMerah ?? 0;
@endphp

<h3>Laporan Monitoring{{ $periodLabel ? ' - ' . $periodLabel : '' }}</h3>
        <!-- GRAFIK PANTAU -->
        <div class="laporan-chart-wrap">
            <div class="laporan-chart-box">
                <div class="laporan-chart-canvas">
                    @if(isset(
                        $chartImage
                    ) && $chartImage)
                        <img src="{{ $chartImage }}" style="width:100%;height:100%;" />
                    @else
                        <p style="text-align:center;color:#888;">(grafik tidak tersedia)</p>
                    @endif
                </div>
                <div class="laporan-chart-legend">
                    <div class="laporan-legend-row">
                        <span class="laporan-legend-swatch laporan-legend-hijau"></span>
                        <span>Hijau (baik)</span>
                    </div>
                    <div class="laporan-legend-row">
                        <span class="laporan-legend-swatch laporan-legend-kuning"></span>
                        <span>Kuning (perlu perhatian)</span>
                    </div>
                    <div class="laporan-legend-row">
                        <span class="laporan-legend-swatch laporan-legend-merah"></span>
                        <span>Merah (bermasalah)</span>
                    </div>
                </div>
            </div>
            <div class="laporan-chart-title">
                {{ $chartTitle ?? 'Grafik Pantau' }}
            </div>
        </div>

<!-- RINGKASAN STATUS -->
<table class="summary">
    <tr>
        <td class="hijau">HIJAU<br>{{ $totalHijau }}</td>
        <td class="kuning">KUNING<br>{{ $totalKuning }}</td>
        <td class="merah">MERAH<br>{{ $totalMerah }}</td>
    </tr>
</table>

<!-- TABEL DATA -->
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Nama Aplikasi</th>
            <th>Status</th>
            <th>Tanggal</th>
            <th>Deskripsi</th>
            <th>Jumlah Lampiran</th>
        </tr>
    </thead>
    <tbody>
        @forelse($monitorings as $idx => $item)
            @php
                $files = $item->file ? json_decode($item->file, true) : [];
                if (!is_array($files)) {
                    $files = $item->file ? [$item->file] : [];
                }
            @endphp
            <tr>
                <td>{{ $idx + 1 }}</td>
                <td>{{ $item->nama_aplikasi }}</td>
                
                <td>
                    <span class="badge {{ $item->status }}">
                        {{ $item->status }}
                    </span>
                </td>
                <td>
                    {{ $item->tanggal
                        ? \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y')
                        : '-' }}
                </td>
                <td>{{ $item->deskripsi ?? '-' }}</td>
                <td>{{ count($files) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7" style="text-align:center;color:#888;">
                    Tidak ada data
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

</body>
</html>
