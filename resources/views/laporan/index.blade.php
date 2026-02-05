@extends('layouts.sidebar')

@section('title', 'Laporan Monitoring')

@section('content')
<style>
/* ===== LAYOUT ===== */
.page-container {
    max-width: 1200px;
    margin: auto;
    padding: 20px;
    font-family: 'Segoe UI', sans-serif;
}

.card {
    background: #fff;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.08);
}

h3 {
    margin-top: 0;
    color: #2c2f7e;
    font-size: 22px;
}

/* ===== FILTER ===== */
.filter-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
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
    transition: 0.2s;
}

.filter-item input:focus {
    outline: none;
    border-color: #2c2f7e;
    box-shadow: 0 0 0 3px rgba(44,47,126,0.15);
}

.filter-action {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.btn {
    height: 38px;
    padding: 0 16px;
    border-radius: 8px;
    font-size: 14px;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    white-space: nowrap;
    transition: 0.2s;
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
    margin: 20px 0 28px;
    flex-wrap: wrap;
}

.status-box {
    flex: 1;
    min-width: 180px;
    padding: 20px;
    border-radius: 12px;
    color: #fff;
    font-weight: 600;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.status-box span {
    display: block;
    font-size: 28px;
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
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

thead {
    background: #2c2f7e;
    color: #fff;
}

th, td {
    padding: 14px 16px;
    vertical-align: middle;
    text-align: left;
    border-bottom: 1px solid #eee;
    font-size: 14px;
}

tbody tr:hover {
    background: #f4f6f9;
}

.badge {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    color: #fff;
    text-transform: capitalize;
    display: inline-block;
}

.badge.hijau { background: #2ecc71; }
.badge.kuning { background: #f1c40f; color: #000; }
.badge.merah { background: #ab8d8a; }

.no-data {
    text-align: center;
    color: #888;
    padding: 30px;
}

/* menambahkan css untuk pagination */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 28px;
    gap: 12px;
}

.page-btn {
    padding: 10px 20px;
    background: #2c2f7e;
    color: white;
    border-radius: 10px;
    text-decoration: none;
    font-weight: bold;
    transition: 0.2s;
}

.page-btn:hover {
    background: #1f2258;
}

.page-btn.disabled {
    background: #ccc;
    pointer-events: none;
    color: #666;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 900px) {
    .filter-row {
        grid-template-columns: 1fr;
    }

    th, td {
        font-size: 13px;
        padding: 10px 12px;
    }

    .status-box span {
        font-size: 24px;
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
        <form method="GET" action="{{ route('laporan') }}" id="filterForm">
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
                           value="{{ request('bulan', $bulan) }}"
                           id="bulanInput">
                </div>                
                <div class="filter-action">
                    <button type="submit" class="btn btn-primary">Terapkan</button>
                </div>
            </div>
        </form>

        <!-- DOWNLOAD SECTION -->
        <div style="margin: 20px 0; padding: 20px; background: #f8f9fa; border-radius: 12px;">
            <h4 style="margin: 0 0 15px 0; color: #2c2f7e;">Download Laporan</h4>
            <div style="display: flex; gap: 12px; flex-wrap: wrap; align-items: end;">
                <div class="filter-item" style="min-width: 150px;">
                    <label>Format</label>
                    <select name="format" id="formatSelect" style="height: 38px; padding: 8px 12px; border-radius: 8px; border: 1px solid #d6d8e6; font-size: 14px; width: 100%;">
                        <option value="pdf">PDF</option>
                        <option value="excel">Excel</option>
                    </select>
                </div>
                <div class="filter-item" style="min-width: 150px;">
                    <label>Periode</label>
                    <select name="periode" id="periodeSelect" style="height: 38px; padding: 8px 12px; border-radius: 8px; border: 1px solid #d6d8e6; font-size: 14px; width: 100%;">
                        <option value="bulan">Per Bulan</option>
                        <option value="tahun">Per Tahun</option>
                        <option value="minggu">Per Minggu</option>
                    </select>
                </div>
                <button type="button" onclick="downloadLaporan()" class="btn btn-secondary" style="height: 38px;">
                    Download
                </button>
            </div>
        </div>

        <script>
        function downloadLaporan() {
            const format = document.getElementById('formatSelect').value;
            const periode = document.getElementById('periodeSelect').value;
            
            // Ambil semua query parameter dari URL saat ini
            const params = new URLSearchParams(window.location.search);
            params.set('periode', periode);
            
            let url;
            if (format === 'excel') {
                url = '{{ route("laporan.excel") }}?' + params.toString();
            } else {
                url = '{{ route("laporan.pdf") }}?' + params.toString();
            }
            
            window.location.href = url;
        }
        </script>

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

{{-- menambahkan pagination --}}
    <div class="pagination-wrapper">
        <a href="{{ $monitorings->previousPageUrl() ?? '#' }}"
           class="page-btn {{ $monitorings->onFirstPage() ? 'disabled' : '' }}">
            ⬅ Previous
        </a>
        <a href="{{ $monitorings->nextPageUrl() ?? '#' }}"
           class="page-btn {{ $monitorings->hasMorePages() ? '' : 'disabled' }}">
            Next ➡
        </a>
    </div>
</div>
@endsection
