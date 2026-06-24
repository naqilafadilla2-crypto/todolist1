@extends('layouts.sidebar')

@section('title', 'Laporan Checklist Perawatan')

@section('content')
<style>
    .report-container {
        max-width: 1200px;
        margin: auto;
        padding: 24px;
    }

    .report-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }

    .report-header h1 {
        margin: 0;
        color: #0a978e;
        font-size: 28px;
    }

    .report-description {
        color: #4b5563;
        margin: 8px 0 0 0;
    }

    .report-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    .report-actions a,
    .report-actions button {
        border: none;
        background: #0a978e;
        color: #fff;
        padding: 12px 18px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .report-actions a:hover,
    .report-actions button:hover {
        background: #08786f;
    }

    .filter-form {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 14px;
        margin-bottom: 24px;
        background: #fff;
        padding: 18px;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .filter-group label {
        font-size: 13px;
        color: #374151;
        font-weight: 600;
    }

    .filter-group input,
    .filter-group select {
        padding: 12px 14px;
        border-radius: 12px;
        border: 1px solid #d1d5db;
        font-size: 14px;
        color: #111827;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
        gap: 18px;
        margin-bottom: 24px;
    }

    .summary-card {
        background: white;
        padding: 20px;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
    }

    .summary-card h3 {
        margin: 0 0 10px 0;
        font-size: 14px;
        font-weight: 700;
        color: #0a978e;
    }

    .summary-card p {
        margin: 0;
        font-size: 34px;
        font-weight: 800;
        color: #111827;
    }

    .table-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
    }

    .report-table {
        width: 100%;
        border-collapse: collapse;
    }

    .report-table th,
    .report-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #e5e7eb;
        font-size: 14px;
        vertical-align: middle;
    }

    .report-table th {
        background: #0a978e;
        color: #fff;
        text-align: left;
    }

    .report-table tr:hover {
        background: #f8fbff;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        color: #fff;
        text-transform: capitalize;
    }

    .status-belum { background: #e74c3c; }
    .status-proses { background: #f59e0b; }
    .status-selesai { background: #10b981; }
</style>

<div class="report-container">
    <div class="report-header">
        <div>
            <h1>Laporan Checklist Perawatan</h1>
            <p class="report-description">Laporan kondisi perawatan perangkat berdasarkan status kuartal dan tanggal expired.</p>
        </div>
        <div class="report-actions">
            <a href="{{ route('maintenance.checklist.index') }}">Kembali ke Checklist</a>
            <a href="{{ route('maintenance.checklist.report.pdf', request()->only('perangkat','status')) }}">Download PDF</a>
            <a href="{{ route('maintenance.checklist.report.excel', request()->only('perangkat','status')) }}">Download Excel</a>
        </div>
    </div>

    <form class="filter-form" method="GET" action="{{ route('maintenance.checklist.report') }}">
        <div class="filter-group">
            <label for="perangkat">Nama Perangkat</label>
            <input id="perangkat" name="perangkat" type="text" value="{{ request('perangkat') }}" placeholder="Cari perangkat...">
        </div>

        <div class="filter-group">
            <label for="status">Status Checklist</label>
            <select id="status" name="status">
                <option value="">Semua Status</option>
                <option value="belum" {{ request('status') === 'belum' ? 'selected' : '' }}>Belum</option>
                <option value="proses" {{ request('status') === 'proses' ? 'selected' : '' }}>Proses</option>
                <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
        </div>

        <div class="filter-group" style="align-self: end;">
            <button type="submit" style="background: #0a978e; color: white;">Terapkan Filter</button>
        </div>
    </form>

    <div class="summary-grid">
        <div class="summary-card">
            <h3>Total Perangkat</h3>
            <p>{{ $summary['total'] }}</p>
        </div>
        <div class="summary-card">
            <h3>Q1 Selesai</h3>
            <p>{{ $summary['q1_done'] }}</p>
        </div>
        <div class="summary-card">
            <h3>Q2 Selesai</h3>
            <p>{{ $summary['q2_done'] }}</p>
        </div>
        <div class="summary-card">
            <h3>Q3 Selesai</h3>
            <p>{{ $summary['q3_done'] }}</p>
        </div>
        <div class="summary-card">
            <h3>Q4 Selesai</h3>
            <p>{{ $summary['q4_done'] }}</p>
        </div>
    </div>

    <div class="table-card">
        <table class="report-table">
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
                @forelse($checklists as $checklist)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $checklist->perangkat }}</td>
                        <td>{{ optional($checklist->expired_date)->format('d/m/Y') ?? '-' }}</td>
                        <td><span class="status-badge status-{{ $checklist->status_q1 }}">{{ $checklist->status_q1 ?? 'belum' }}</span></td>
                        <td><span class="status-badge status-{{ $checklist->status_q2 }}">{{ $checklist->status_q2 ?? 'belum' }}</span></td>
                        <td><span class="status-badge status-{{ $checklist->status_q3 }}">{{ $checklist->status_q3 ?? 'belum' }}</span></td>
                        <td><span class="status-badge status-{{ $checklist->status_q4 }}">{{ $checklist->status_q4 ?? 'belum' }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding: 24px; color: #6b7280;">Belum ada data checklist perawatan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
