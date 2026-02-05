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
    </style>
</head>
<body>

@php
    $totalHijau = 0;
    $totalKuning = 0;
    $totalMerah = 0;

    foreach ($monitorings as $m) {
        if ($m->status === 'hijau') $totalHijau++;
        elseif ($m->status === 'kuning') $totalKuning++;
        elseif ($m->status === 'merah') $totalMerah++;
    }
@endphp

<h3>Laporan Monitoring{{ $periodLabel ? ' - ' . $periodLabel : '' }}</h3>

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
