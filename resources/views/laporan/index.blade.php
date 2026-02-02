@extends('layouts.sidebar')

@section('title', 'Laporan Monitoring')

@section('content')
<style>
/* ===== LAYOUT ===== */
.page-container {
    max-width: 1200px;
    margin: auto;
    padding: 20px;
}

.card {
    background: #fff;
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.06);
}

h3 {
    margin-top: 0;
    color: #2c2f7e;
}

/* ===== FILTER ===== */
.filter-row {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 14px;
    margin-bottom: 20px;
    align-items: end;
}

.filter-item {
    display: flex;
    flex-direction: column;
}

.filter-item label {
    font-size: 13px;
    font-weight: 600;
    color: #2c2f7e;
    margin-bottom: 6px;
}

.filter-item input {
    height: 38px;
    padding: 8px 12px;
    border-radius: 8px;
    border: 1px solid #d6d8e6;
    font-size: 14px;
}

.filter-item input:focus {
    outline: none;
    border-color: #2c2f7e;
    box-shadow: 0 0 0 2px rgba(44,47,126,0.15);
}

.filter-action {
    display: flex;
    gap: 10px;
}

.btn {
    height: 38px;
    padding: 0 16px;
    border-radius: 8px;
    font-size: 14px;
    border: none;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    white-space: nowrap;
}

.btn-primary {
    background: #2c2f7e;
    color: #fff;
}

.btn-secondary {
    background: #f1c40f;
    color: #000;
}

.btn:hover {
    opacity: 0.9;
}

/* ===== STATUS SUMMARY ===== */
.status-summary {
    display: flex;
    gap: 16px;
    margin: 18px 0 24px;
    flex-wrap: wrap;
}

.status-box {
    flex: 1;
    min-width: 180px;
    padding: 16px;
    border-radius: 12px;
    color: #fff;
    font-weight: 600;
}

.status-box span {
    display: block;
    font-size: 26px;
    margin-top: 6px;
}

.status-hijau { background: #2ecc71; }
.status-kuning { background: #f1c40f; color: #000; }
.status-merah { background: #e74c3c; }

/* ===== TABLE ===== */
table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
}

thead {
    background: #2c2f7e;
    color: #fff;
}

th, td {
    padding: 12px 14px;
    border-bottom: 1px solid #eee;
    vertical-align: top;
}

tbody tr:hover {
    background: #f8f9fa;
}

.badge {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    color: #fff;
    text-transform: capitalize;
}

.badge.hijau { background: #2ecc71; }
.badge.kuning { background: #f1c40f; color: #000; }
.badge.merah { background: #e74c3c; }

.no-data {
    text-align: center;
    color: #888;
    padding: 30px;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 900px) {
    .filter-row {
        grid-template-columns: 1fr;
    }
}
</style>

@php
    $totalHijau  = $monitorings->where('status', 'hijau')->count();
    $totalKuning = $monitorings->where('status', 'kuning')->count();
    $totalMerah  = $monitorings->where('status', 'merah')->count();
@endphp

<div class="page-container">
    <div class="card">
        <h3>Laporan Monitoring</h3>

        <!-- RINGKASAN STATUS -->
        <div class="status-summary">
            <div class="status-box status-hijau">
                Status Hijau
                <span>{{ $totalHijau }}</span>
            </div>
            <div class="status-box status-kuning">
                Status Kuning
                <span>{{ $totalKuning }}</span>
            </div>
            <div class="status-box status-merah">
                Status Merah
                <span>{{ $totalMerah }}</span>
            </div>
        </div>

        <!-- FILTER -->
        <form method="GET" action="{{ route('laporan') }}">
            <div class="filter-row">

                <div class="filter-item">
                    <label>Nama Aplikasi</label>
                    <input type="text" name="aplikasi"
                           value="{{ request('aplikasi') }}"
                           placeholder="Cari aplikasi...">
                </div>

                <div class="filter-item">
                    <label>Bulan</label>
                    <input type="month" name="bulan"
                           value="{{ request('bulan', $bulan) }}">
                </div>

                <div class="filter-item">
                    <label>Tanggal Dari</label>
                    <input type="date" name="tanggal_dari"
                           value="{{ request('tanggal_dari') }}">
                </div>

                <div class="filter-item">
                    <label>Tanggal Sampai</label>
                    <input type="date" name="tanggal_sampai"
                           value="{{ request('tanggal_sampai') }}">
                </div>

                <div class="filter-action">
                    <button type="submit" class="btn btn-primary">Terapkan</button>
                    <a href="{{ route('laporan.pdf', request()->query()) }}"
                       class="btn btn-secondary">
                        Download PDF
                    </a>
                </div>

            </div>
        </form>

        <!-- TABEL -->
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Aplikasi</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Deskripsi</th>
                        <th>Jumlah Lampiran</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($monitorings as $index => $item)
                    @php
                        $files = $item->file ? json_decode($item->file, true) : [];
                        $files = is_array($files) ? $files : [];
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->nama_aplikasi }}</td>
                        <td>
                            <span class="badge {{ $item->status }}">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td>
                            {{ $item->created_at
                                ? $item->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i')
                                : '-' }}
                        </td>
                        <td>{{ $item->deskripsi ?? '-' }}</td>
                        <td>{{ count($files) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="no-data">
                            Tidak ada data
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
