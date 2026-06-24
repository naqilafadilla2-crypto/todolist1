<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Checklist Perawatan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 16px;
        }
        .header {
            margin-bottom: 18px;
            border-bottom: 2px solid #0a978e;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            color: #0a978e;
        }
        .header p {
            margin: 6px 0 0 0;
            font-size: 12px;
            color: #555;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        .summary-table td {
            padding: 10px;
            border: 1px solid #d1d5db;
            background: #f8fafc;
            font-size: 12px;
        }
        .summary-title {
            font-weight: 700;
            color: #0a978e;
        }
        table {
            width: 100%;
            border-collapse: collapse;
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
        .status-label {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 999px;
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            text-transform: capitalize;
        }
        .status-belum { background: #e74c3c; }
        .status-proses { background: #f59e0b; color: #111; }
        .status-selesai { background: #10b981; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Checklist Perawatan</h1>
        <p>Dicetak: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <table class="summary-table">
        <tr>
            <td class="summary-title">Total Perangkat</td>
            <td>{{ $checklists->count() }}</td>
            <td class="summary-title">Q1 Selesai</td>
            <td>{{ $checklists->filter(fn($item) => $item->status_q1 === 'selesai')->count() }}</td>
        </tr>
        <tr>
            <td class="summary-title">Q2 Selesai</td>
            <td>{{ $checklists->filter(fn($item) => $item->status_q2 === 'selesai')->count() }}</td>
            <td class="summary-title">Q3 Selesai</td>
            <td>{{ $checklists->filter(fn($item) => $item->status_q3 === 'selesai')->count() }}</td>
        </tr>
        <tr>
            <td class="summary-title">Q4 Selesai</td>
            <td>{{ $checklists->filter(fn($item) => $item->status_q4 === 'selesai')->count() }}</td>
            <td class="summary-title"></td>
            <td></td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Perangkat</th>
                <th>Expired Date</th>
                <th>Status Q1</th>
                <th>Status Q2</th>
                <th>Status Q3</th>
                <th>Status Q4</th>
            </tr>
        </thead>
        <tbody>
            @foreach($checklists as $index => $checklist)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $checklist->perangkat }}</td>
                    <td>{{ optional($checklist->expired_date)->format('d/m/Y') ?? '-' }}</td>
                    <td><span class="status-label status-{{ $checklist->status_q1 }}">{{ $checklist->status_q1 ?? 'belum' }}</span></td>
                    <td><span class="status-label status-{{ $checklist->status_q2 }}">{{ $checklist->status_q2 ?? 'belum' }}</span></td>
                    <td><span class="status-label status-{{ $checklist->status_q3 }}">{{ $checklist->status_q3 ?? 'belum' }}</span></td>
                    <td><span class="status-label status-{{ $checklist->status_q4 }}">{{ $checklist->status_q4 ?? 'belum' }}</span></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
