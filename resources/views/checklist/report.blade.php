@extends('layouts.sidebar')

@section('title', 'Laporan Checklist Expired Date')

@section('content')
<style>
    .page-container {
        max-width: 1200px;
        margin: auto;
        padding: 20px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .page-header h2 {
        color: #0a978e;
        font-size: 28px;
        margin: 0;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 28px;
        margin-bottom: 24px;
    }

    .summary-card {
        border-radius: 16px;
        padding: 24px 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.07);
        color: #fff;
        min-height: 130px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .summary-card h3 {
        margin: 0 0 10px;
        font-size: 16px;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .summary-card p {
        margin: 0;
        font-size: 44px;
        font-weight: 800;
        line-height: 1;
    }

    .summary-card span {
        margin-top: 8px;
        color: rgba(255,255,255,0.85);
        font-size: 14px;
    }

    .summary-card.total { background: #0f766e; }
    .summary-card.hijau { background: #27ae60; }
    .summary-card.kuning { background: #f1c40f; color: #111; }
    .summary-card.merah { background: #e74c3c; }

    .filter-bar {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 14px;
        align-items: flex-end;
        margin-bottom: 24px;
        background: #fff;
        border-radius: 16px;
        padding: 18px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.05);
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .filter-group label {
        font-size: 13px;
        color: #555;
        font-weight: 600;
    }

    .filter-group input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 12px;
        font-size: 14px;
        color: #111;
    }

    .filter-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-filter,
    .btn-reset {
        border: none;
        border-radius: 12px;
        padding: 12px 18px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .btn-filter {
        background: #0a978e;
        color: #fff;
    }

    .btn-filter:hover {
        background: #08786f;
    }

    .btn-reset {
        background: #f1f5f9;
        color: #111;
    }

    .btn-reset:hover {
        background: #e2e8f0;
    }

    .download-panel {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        padding: 22px 24px;
        margin-bottom: 24px;
    }

    .download-panel h3 {
        margin: 0 0 18px;
        color: #0a978e;
        font-size: 20px;
    }

    .download-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
        align-items: center;
    }

    .download-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .download-group label {
        font-size: 13px;
        color: #27485a;
        font-weight: 600;
    }

    .download-group select {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #dae1e7;
        border-radius: 14px;
        font-size: 14px;
        color: #1f2937;
        background: #fff;
    }

    .download-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        min-height: 52px;
        border: none;
        border-radius: 14px;
        background: #f2c94c;
        color: #163b5b;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .download-button:hover {
        background: #e2b332;
    }

    .download-button svg {
        margin-right: 10px;
    }

    .table-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 12px 30px rgba(0,0,0,0.05);
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background: #0a978e;
        color: #fff;
    }

    th, td {
        padding: 14px 16px;
        text-align: left;
        border-bottom: 1px solid #f1f3f8;
    }

    tbody tr:hover {
        background: #f8fbff;
    }

    .status-label {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 88px;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        color: #fff;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }

    .HIJAU { background: #27ae60; }
    .KUNING { background: #f39c12; }
    .MERAH { background: #e74c3c; }

    .tag-expired {
        display: inline-block;
        padding: 6px 12px;
        background: #fdecea;
        color: #c0392b;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
    }

    .link-back {
        display: inline-block;
        padding: 10px 18px;
        border-radius: 10px;
        background: #0a978e;
        color: #fff;
        text-decoration: none;
        font-size: 14px;
        transition: background 0.3s ease;
    }

    .link-back:hover {
        background: #08786f;
    }
</style>

<div class="page-container">
    <div class="page-header">
        <div>
            <h2>Laporan Checklist Expired Date</h2>
            <p style="margin: 8px 0 0; color: #555;">Ringkasan status dan daftar perangkat dengan tanggal kadaluarsa.</p>
        </div>
    </div>

    <div class="download-panel">
        <h3>Download Laporan</h3>
        <form id="download-form" method="GET" action="{{ route('checklist.report.pdf') }}">
            <div class="download-grid">
                <div class="download-group">
                    <label for="format">Format</label>
                    <select id="format" name="format">
                        <option value="pdf">PDF</option>
                        <option value="excel">Excel</option>
                    </select>
                </div>

                <div class="download-group">
                    <label for="periode">Periode</label>
                    <select id="periode" name="periode">
                        <option value="" {{ request('periode') === null || request('periode') === '' ? 'selected' : '' }}>Pilih Periode</option>
                        <option value="bulan" {{ request('periode') === 'bulan' ? 'selected' : '' }}>Per Bulan</option>
                        <option value="minggu" {{ request('periode') === 'minggu' ? 'selected' : '' }}>Per Minggu</option>
                        <option value="hari" {{ request('periode') === 'hari' ? 'selected' : '' }}>Per Hari</option>
                        <option value="tahun" {{ request('periode') === 'tahun' ? 'selected' : '' }}>Per Tahun</option>
                    </select>
                </div>

                <div class="download-group" id="year-group" style="display: none;">
                    <label for="tahun">Tahun</label>
                    <select id="tahun" name="tahun">
                        @php $currentYear = now()->year; @endphp
                        @for ($i = 0; $i < 6; $i++)
                            @php $year = $currentYear - $i; @endphp
                            <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endfor
                    </select>
                </div>

                <div class="download-group" style="margin-top: 24px;">
                    <button type="submit" class="download-button">Download</button>
                </div>
            </div>
            <input type="hidden" name="nama_perangkat" value="{{ request('nama_perangkat') }}">
            <input type="hidden" name="tanggal_dari" value="{{ request('tanggal_dari') }}">
            <input type="hidden" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}">
        </form>
    </div>

    <form action="{{ route('checklist.report') }}" method="GET" class="filter-bar">
        <div class="filter-group">
            <label for="nama_perangkat">Nama Perangkat</label>
            <input type="text" name="nama_perangkat" id="nama_perangkat" placeholder="Cari nama perangkat" value="{{ old('nama_perangkat', $filterNama ?? '') }}">
        </div>
        <div class="filter-group">
            <label for="tanggal_dari">Tanggal Kadaluarsa Dari</label>
            <input type="date" name="tanggal_dari" id="tanggal_dari" value="{{ old('tanggal_dari', $filterTanggalDari ?? '') }}">
        </div>
        <div class="filter-group">
            <label for="tanggal_sampai">Tanggal Kadaluarsa Sampai</label>
            <input type="date" name="tanggal_sampai" id="tanggal_sampai" value="{{ old('tanggal_sampai', $filterTanggalSampai ?? '') }}">
        </div>
        <div class="filter-actions">
            <button type="submit" class="btn-filter">Filter</button>
            <a href="{{ route('checklist.report') }}" class="btn-reset">Reset</a>
        </div>
    </form>

    <div class="summary-grid">
        <div class="summary-card total">
            <h3>Total Item</h3>
            <p>{{ $total }}</p>
            <span>Total perangkat yang ditampilkan</span>
        </div>
        <div class="summary-card hijau">
            <h3>Status Hijau</h3>
            <p>{{ $hijau }}</p>
            <span>Perangkat dalam kondisi aman</span>
        </div>
        <div class="summary-card kuning">
            <h3>Status Kuning</h3>
            <p>{{ $kuning }}</p>
            <span>Perangkat mendekati kadaluarsa</span>
        </div>
        <div class="summary-card merah">
            <h3>Status Merah</h3>
            <p>{{ $merah }}</p>
            <span>Perangkat expired atau kritis</span>
        </div>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Perangkat</th>
                    <th>Tanggal Kadaluarsa</th>
                    <th>Status</th>
                    <th>Sisa Hari</th>
                    <th>Peringatan</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->nama_perangkat }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_kadaluarsa)->format('d/m/Y') }}</td>
                        <td><span class="status-label {{ $item->status }}">{{ $item->status }}</span></td>
                        <td>{{ $item->sisa_hari }}</td>
                        <td>{!! nl2br(e($item->peringatan)) !!}</td>
                        <td>{{ $item->keterangan }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding: 24px; text-align: center; color: #7a7a7a;">Belum ada data checklist.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const downloadForm = document.getElementById('download-form');
        const formatSelect = document.getElementById('format');
        const periodeSelect = document.getElementById('periode');
        const yearGroup = document.getElementById('year-group');

        function updateYearGroup() {
            if (periodeSelect && yearGroup) {
                yearGroup.style.display = periodeSelect.value === 'tahun' ? 'flex' : 'none';
            }
        }

        if (downloadForm && formatSelect) {
            downloadForm.addEventListener('submit', function (event) {
                const format = formatSelect.value;

                if (format === 'excel') {
                    downloadForm.action = '{{ route('checklist.report.excel') }}';
                } else {
                    downloadForm.action = '{{ route('checklist.report.pdf') }}';
                }
            });
        }

        if (periodeSelect) {
            periodeSelect.addEventListener('change', updateYearGroup);
            updateYearGroup();
        }

    });
</script>
@endsection
