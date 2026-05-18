@extends('layouts.sidebar')

@section('title', 'Checklist Expired Date')

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
        margin-bottom: 20px;
    }

    .page-header h2 {
        color: #0a978e;
        font-size: 24px;
        margin: 0;
    }

    .btn-add {
        background: #0a978e;
        color: #fff;
        padding: 10px 18px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 14px;
        transition: background 0.3s ease;
    }

    .btn-add:hover {
        background: #08786f;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        padding: 14px 18px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .table-card {
        width: 100%;
        background: #fff;
        border-radius: 14px;
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
    }

    tbody tr {
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

    .HIJAU {
        background: #27ae60;
    }

    .KUNING {
        background: #f39c12;
    }

    .MERAH {
        background: #e74c3c;
    }

    .aman {
        color: #155724;
        font-weight: 700;
    }

    .warning {
        color: #c0392b;
        font-weight: 700;
    }

    .btn-action {
        display: inline-block;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 13px;
        color: #fff;
        text-decoration: none;
        margin-right: 6px;
    }

    .btn-edit {
        background: #3498db;
    }

    .btn-edit:hover {
        background: #2980b9;
    }

    .btn-delete {
        background: #e74c3c;
        border: none;
        cursor: pointer;
    }

    .btn-delete:hover {
        background: #c0392b;
    }

    .inline-form {
        display: inline;
    }

    .no-data {
        text-align: center;
        padding: 30px;
        color: #7a7a7a;
    }
</style>

<div class="page-container">
    <div class="page-header">
        <h2>Checklist Expired Date</h2>
        <a href="{{ route('checklist.create') }}" class="btn-add">+ Tambah Data</a>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Perangkat</th>
                    <th>Tanggal Kadaluarsa</th>
                    <th>Status</th>
                    <th>Waktu</th>
                    <th>Peringatan</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->nama_perangkat }}</td>
                        <td>{{ $item->tanggal_kadaluarsa }}</td>
                        <td><span class="status-label {{ $item->status }}">{{ $item->status }}</span></td>
                        <td>{{ $item->sisa_hari }}</td>
                        <td class="{{ $item->peringatan == 'AMAN' ? 'aman' : 'warning' }}">{{ $item->peringatan }}</td>
                        <td>{{ $item->keterangan }}</td>
                        <td>
                            <a href="{{ route('checklist.edit', $item->id) }}" class="btn-action btn-edit">Edit</a>
                            <form action="{{ route('checklist.destroy', $item->id) }}" method="POST" class="inline-form" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="no-data">Belum ada data checklist.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
