<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Detail Monitoring</title>

    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #333;
            background: #fff;
            margin: 0;
            padding: 0;
        }

        /* HEADER */
        .header {
            text-align: center;
            border-bottom: 3px solid #2c2f7e;
            padding: 10px 0 12px;
            margin-bottom: 18px;
        }

        .header h2 {
            margin: 0;
            font-size: 18px;
            letter-spacing: 1px;
            color: #2c2f7e;
        }

        /* BOX */
        .box {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 12px 14px;
            margin-bottom: 14px;
        }

        .box-title {
            font-weight: bold;
            margin-bottom: 8px;
            color: #2c2f7e;
            font-size: 12px;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 8px 10px;
            border: 1px solid #ddd;
            vertical-align: top;
        }

        .label {
            width: 30%;
            background: #f4f6fa;
            font-weight: bold;
        }

        .value {
            width: 70%;
        }

        /* STATUS */
        .badge {
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
            text-transform: uppercase;
        }

        .hijau { background: #28a745; color:#fff; }
        .kuning { background: #f1c40f; color:#000; }
        .merah { background: #dc3545; color:#fff; }

        /* DESKRIPSI */
        .deskripsi {
            line-height: 1.6;
            min-height: 40px;
        }

        /* LAMPIRAN */
        .lampiran-grid {
            margin-top: 4px;
            text-align: left;
        }

        .lampiran-item {
            display: inline-block;
            margin: 4px 6px;
            padding: 3px;
            border: 1px solid #ccc;
            border-radius: 6px;
            background: #fafafa;
        }

        .lampiran-item img {
            width: 120px;
            height: 90px;
            object-fit: cover;
            display: block;
        }

        /* FOOTER */
        .footer {
            margin-top: 18px;
            font-size: 10px;
            text-align: right;
            color: #666;
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <div class="header">
        <h2>LAPORAN DETAIL MONITORING</h2>
    </div>

    <!-- DATA UTAMA -->
    <div class="box">
        <table>
            <tr>
                <td class="label">Nama Aplikasi</td>
                <td class="value">{{ $monitoring->nama_aplikasi }}</td>
            </tr>
            <tr>
                <td class="label">Status</td>
                <td class="value">
                    <span class="badge {{ $monitoring->status }}">
                        {{ strtoupper($monitoring->status) }}
                    </span>
                </td>
            </tr>
            <tr>
                <td class="label">Tanggal</td>
                <td class="value">
                    {{ $monitoring->created_at ? $monitoring->created_at->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') : '-' }}
                </td>
            </tr>
        </table>
    </div>

    <!-- DESKRIPSI -->
    <div class="box">
        <div class="box-title">Deskripsi</div>
        <div class="deskripsi">
            {{ $monitoring->deskripsi ?? '-' }}
        </div>
    </div>

    <!-- LAMPIRAN -->
    <div class="box">
        <div class="box-title">Lampiran</div>

        @php
            $files = $monitoring->file ? json_decode($monitoring->file, true) : [];
            if (!is_array($files)) $files = [];
        @endphp

        <div class="lampiran-grid">
            @forelse($files as $file)
                @php
                    $path = storage_path('app/public/' . $file);
                    if (!file_exists($path)) continue;
                    $mime = mime_content_type($path);
                    $base64 = base64_encode(file_get_contents($path));
                @endphp

                <div class="lampiran-item">
                    <img src="data:{{ $mime }};base64,{{ $base64 }}">
                </div>
            @empty
                <span style="color:#999;">Tidak ada lampiran</span>
            @endforelse
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        Dicetak pada {{ now()->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') }}
    </div>

</body>
</html>
