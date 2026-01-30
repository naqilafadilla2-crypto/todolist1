@extends('layouts.sidebar')

@section('title', 'Detail Monitoring')

@section('content')
<style>
    .page-container { max-width: 900px; margin: auto; background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
    .btn-back { background: #2c2f7e; color: #fff; padding: 6px 14px; border-radius: 6px; text-decoration: none; font-size: 14px; }
    .btn-back:hover { background: #1f2258; }
    .btn-download { background: #27ae60; color: #fff; padding: 6px 14px; border-radius: 6px; text-decoration: none; font-size: 14px; }
    .btn-download:hover { background: #229954; }
    .field { margin-bottom: 14px; }
    .label { font-weight: 600; color: #2c2f7e; margin-bottom: 6px; }
    .value { background: #f6f7fb; padding: 10px 12px; border-radius: 8px; }
    .file-image { width: 180px; height: 180px; object-fit: cover; border-radius: 8px; border: 1px solid #e1e4f0; display:block; }
    .file-link { color: #2c2f7e; text-decoration: none; font-weight: 600; }
    .files-grid { display:flex; flex-wrap:wrap; gap:12px; }
    .file-card { border: 1px solid #e1e4f0; padding: 10px; border-radius: 8px; background:#fafbff; }
</style>

<div class="page-container">
    <div class="page-header">
        <h3 style="margin:0;color:#2c2f7e;">Detail Monitoring</h3>
        <div style="display:flex;gap:8px;">
            <a href="{{ route('monitoring.user.dashboard') }}" class="btn-back">Kembali</a>
            <a href="{{ route('monitoring.download', $monitoring->id) }}" class="btn-download">Download PDF</a>
        </div>
    </div>

    <div class="field">
        <div class="label">Nama Aplikasi</div>
        <div class="value">{{ $monitoring->nama_aplikasi }}</div>
    </div>

    <div class="field">
        <div class="label">Status</div>
        <div class="value text-capitalize">{{ $monitoring->status }}</div>
    </div>

    <div class="field">
        <div class="label">Tanggal</div>
        <div class="value">{{ $monitoring->tanggal ? \Carbon\Carbon::parse($monitoring->tanggal)->format('d/m/Y') : '-' }}</div>
    </div>

    <div class="field">
        <div class="label">Deskripsi</div>
        <div class="value">{{ $monitoring->deskripsi ?? '-' }}</div>
    </div>

    <div class="field">
        <div class="label">File</div>

        @php
            $fileData = $monitoring->file;
            $files = $fileData ? json_decode($fileData, true) : [];
            if (!is_array($files)) {
                $files = $fileData ? [$fileData] : [];
            }
            $imageExtensions = ['jpg','jpeg','png','gif','webp','bmp'];
            $pdfExtensions = ['pdf'];
        @endphp

        @if(!empty($files))
            <div class="value files-grid">
                @foreach($files as $file)
                    @php
                        $fileUrl = asset('storage/' . str_replace('\\','/', $file));
                        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                        $isImage = in_array($ext, $imageExtensions);
                        $isPdf = in_array($ext, $pdfExtensions);
                    @endphp

                    <div class="file-card">
                        {{-- Preview --}}
                        @if($isImage)
                            <img src="{{ $fileUrl }}" alt="File" class="file-image" style="max-width: 300px; max-height: 300px; margin-bottom: 10px;">
                        @elseif($isPdf)
                            <embed src="{{ $fileUrl }}" type="application/pdf" width="100%" height="400px" style="margin-bottom: 10px;">
                        @endif

                        {{-- Tombol aksi --}}
                        <div style="display:flex;gap:10px;">
                            <a href="{{ $fileUrl }}" target="_blank" class="file-link">
                                👁 Lihat File
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="value" style="color:#999;">-</div>
        @endif
    </div>
</div>
@endsection
