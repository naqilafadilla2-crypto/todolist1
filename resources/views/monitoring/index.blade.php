@extends('layouts.sidebar')

@section('title', 'Monitoring')

@section('content')
<style>
    .page-container {
        max-width: 1400px;
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
        color: #2c2f7e;
        font-size: 24px;
        margin: 0;
    }

    .btn-add {
        background: #2c2f7e;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 14px;
    }

    table {
        width: 100%;
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    thead {
        background: #2c2f7e;
        color: white;
    }

    th, td {
        padding: 12px 15px;
        vertical-align: top;
    }

    tbody tr:hover {
        background: #f8f9fa;
    }

    .status-hijau { background: #2ecc71; color: white; padding: 4px 12px; border-radius: 12px; }
    .status-kuning { background: #f1c40f; color: black; padding: 4px 12px; border-radius: 12px; }
    .status-merah { background: #e74c3c; color: white; padding: 4px 12px; border-radius: 12px; }

    .btn-action {
        padding: 6px 12px;
        border-radius: 6px;
        color: white;
        text-decoration: none;
        font-size: 13px;
        margin-right: 4px;
        display: inline-block;
    }

    .btn-view { background: #3498db; }
    .btn-edit { background: #28a745; }
    .btn-delete { background: #dc3545; border: none; cursor: pointer; }

    .thumb-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #2c2f7e;
        cursor: pointer;
    }

    .photo-container {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    /* ===== CUSTOM PAGINATION BUTTON ===== */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 25px;
        gap: 15px;
    }

    .page-btn {
        padding: 10px 22px;
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
</style>

<div class="page-container">
    <div class="page-header">
        <h2>Monitoring Semua Aplikasi</h2>
        <a href="{{ route('monitoring.create') }}" class="btn-add">+ Tambah Data</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Aplikasi</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Foto</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
        @forelse($monitorings as $index => $monitoring)
            <tr>
                <td>{{ $monitorings->firstItem() + $index }}</td>
                <td>{{ $monitoring->nama_aplikasi }}</td>

                <td>
                    <span class="status-{{ $monitoring->status }}">
                        {{ ucfirst($monitoring->status) }}
                    </span>
                </td>

                <td>{{ $monitoring->created_at->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') }}</td>

                <td>
                    @php
                        $files = $monitoring->file ? json_decode($monitoring->file, true) : [];
                    @endphp

                    @if(count($files))
                        <div class="photo-container">
                            @foreach($files as $file)
                                <img src="{{ asset('storage/'.$file) }}" class="thumb-img"
                                     onclick="window.open(this.src,'_blank')">
                            @endforeach
                        </div>
                    @else
                        -
                    @endif
                </td>

                <td>{{ \Illuminate\Support\Str::limit($monitoring->deskripsi ?? '-', 50) }}</td>

                <td>
                    <a href="{{ route('monitoring.show',$monitoring->id) }}" class="btn-action btn-view">Detail</a>
                    <a href="{{ route('monitoring.edit',$monitoring->id) }}" class="btn-action btn-edit">Edit</a>

                    <form action="{{ route('monitoring.destroy',$monitoring->id) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn-action btn-delete"
                            onclick="return confirm('Yakin ingin hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" style="text-align:center;padding:40px;color:#999;">
                    Belum ada data monitoring
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    {{-- BUTTON PREVIOUS & NEXT --}}
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
